<?php

/*
|--------------------------------------------------------------------------
| RUTE API — RESTful JSON Endpoints
|--------------------------------------------------------------------------
| Menyediakan layanan REST API untuk komunikasi frontend (JavaScript)
| dengan backend. Menggunakan autentikasi Laravel Sanctum (token bearer).
|
| BASE URL: /api
| AUTH: Bearer Token (Sanctum)
| RATE LIMIT: 60 request/menit (default), 10/menit untuk auth
|--------------------------------------------------------------------------
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\GaleriApiController;

/*
 * ============================================
 *  RATE LIMITER — Batasan jumlah request
 * ============================================
 * 1. api   → 60 request/menit per user/ip
 * 2. auth  → 10 request/menit (login/register)
 * 3. orders → 30 request/menit (manipulasi pesanan)
 */
RateLimiter::for('api', fn(Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));
RateLimiter::for('auth', fn() => Limit::perMinute(10)->by(request()->ip()));
RateLimiter::for('orders', fn(Request $request) => Limit::perMinute(30)->by($request->user()?->id ?: $request->ip()));

// ============================================
//  PUBLIC ROUTES — Tanpa perlu token
// ============================================

// Auth: register & login (rate limit ketat: 10x/menit)
Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:auth');
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:auth');

// Katalog layanan publik
Route::prefix('layanan')->group(function () {
    Route::get('/', [LayananController::class, 'index']);               // daftar semua layanan
    Route::get('/{layanan}', [LayananController::class, 'show']);       // detail satu layanan
    Route::get('/kategori/{kategori}', [LayananController::class, 'filterByCategory']); // filter by kategori
});

// Galeri publik
Route::prefix('galeri')->group(function () {
    Route::get('/', [GaleriApiController::class, 'index']);             // semua karya galeri
    Route::get('/kategori', [GaleriApiController::class, 'categories']); // daftar kategori
    Route::get('/{kategori}/jenis', [GaleriApiController::class, 'jenisList']); // filter by kategori
});

// ============================================
//  PROTECTED ROUTES — Wajib login (Sanctum)
// ============================================
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    // Auth: logout & profil
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // ========================================
    //  KERANJANG BELANJA
    // ========================================
    Route::prefix('keranjang')->group(function () {
        Route::get('/', [KeranjangController::class, 'index']);                         // lihat keranjang
        Route::get('/count', [KeranjangController::class, 'getCount']);                  // jumlah item
        Route::get('/check/{idLayanan}', [KeranjangController::class, 'checkItemInCart']); // cek apakah item sudah ada
        Route::post('/item', [KeranjangController::class, 'addItem']);                   // tambah item
        Route::put('/item/{idDetailKeranjang}', [KeranjangController::class, 'updateItem']); // ubah jumlah
        Route::delete('/item/{idDetailKeranjang}', [KeranjangController::class, 'removeItem']); // hapus item
        Route::delete('/clear', [KeranjangController::class, 'clear']);                 // kosongkan keranjang
    });

    // ========================================
    //  PESANAN / ORDER
    // ========================================
    Route::prefix('pesanan')->group(function () {
        Route::get('/', [PesananController::class, 'index']);       // riwayat pesanan user
        Route::post('/', [PesananController::class, 'store']);      // buat pesanan baru
        Route::get('/{pesanan}', [PesananController::class, 'show']); // detail pesanan
        Route::put('/{pesanan}/cancel', [PesananController::class, 'cancel']); // batalkan pesanan
        Route::get('/{pesanan}/invoice', [PesananController::class, 'getInvoice']); // ambil data invoice

        // Pembayaran per pesanan
        Route::prefix('{pesananId}/pembayaran')->group(function () {
            Route::post('/upload', [PembayaranController::class, 'upload']);    // upload bukti transfer
            Route::get('/status', [PembayaranController::class, 'getStatus']);  // cek status pembayaran
        });
    });

    // ========================================
    //  NOTIFIKASI PUSH
    // ========================================
    Route::prefix('notifikasi')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('api.notifikasi.index');
        Route::get('/unread-count', [NotifikasiController::class, 'unreadCount'])->name('api.notifikasi.unread');
        Route::post('/mark-all-read', [NotifikasiController::class, 'markAllAsRead'])->name('api.notifikasi.markAllAsRead');
        Route::post('/{notifikasi}/read', [NotifikasiController::class, 'markAsRead']);
        Route::delete('/{notifikasi}', [NotifikasiController::class, 'destroy']);
    });

    // ========================================
    //  ADMIN ONLY — Manajemen & Laporan
    // ========================================
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Manajemen pesanan (admin bisa lihat & edit semua pesanan)
        Route::prefix('pesanan')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\AdminPesananController::class, 'index']);
            Route::get('/{pesanan}', [\App\Http\Controllers\Api\Admin\AdminPesananController::class, 'show']);
            Route::put('/{pesanan}/status', [\App\Http\Controllers\Api\Admin\AdminPesananController::class, 'updateStatus']);
            Route::post('/{pesanan}/note', [\App\Http\Controllers\Api\Admin\AdminPesananController::class, 'addNote']);
        });

        // Verifikasi pembayaran
        Route::prefix('pembayaran')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\AdminPembayaranController::class, 'index']);
            Route::put('/{pembayaran}/verify', [\App\Http\Controllers\Api\Admin\AdminPembayaranController::class, 'verify']);
            Route::put('/{pembayaran}/reject', [\App\Http\Controllers\Api\Admin\AdminPembayaranController::class, 'reject']);
        });

        // Statistik & grafik dashboard admin
        Route::get('/dashboard/stats', [\App\Http\Controllers\Api\Admin\AdminDashboardController::class, 'getStats']);
        Route::get('/dashboard/chart-data', [\App\Http\Controllers\Api\Admin\AdminDashboardController::class, 'getChartData']);
    });
});

// Health check endpoint — monitoring sederhana
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});
