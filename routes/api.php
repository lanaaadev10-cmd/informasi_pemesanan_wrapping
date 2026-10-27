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

use App\Http\Controllers\Api\Admin\AdminDashboardController;
use App\Http\Controllers\Api\Admin\AdminPembayaranController;
use App\Http\Controllers\Api\Admin\AdminPesananController;
use App\Http\Controllers\Api\Admin\AdminReservasiController;
use App\Http\Controllers\Api\AutentikasiController;
use App\Http\Controllers\Api\GaleriController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Api\PenilaianController;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\ReservasiController;
use App\Http\Controllers\Api\ReservasiPublikController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/*
 * ============================================
 *  RATE LIMITER — Batasan jumlah request
 * ============================================
 * 1. api     → 60 request/menit per user/ip
 * 2. auth    → 10 request/menit (login/register)
 * 3. orders  → 30 request/menit (manipulasi pesanan)
 * 4. booking → 3 request/menit (submit form/upload bukti)
 */
RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));
RateLimiter::for('auth', fn () => Limit::perMinute(10)->by(request()->ip()));
RateLimiter::for('orders', fn (Request $request) => Limit::perMinute(30)->by($request->user()?->id ?: $request->ip()));
RateLimiter::for('booking', fn (Request $request) => Limit::perMinute(15)->by($request->user()?->id ?: $request->ip()));

// ============================================
//  PUBLIC ROUTES — Tanpa perlu token
// ============================================

// Auth: register & login (rate limit ketat: 10x/menit)
Route::post('/auth/register', [AutentikasiController::class, 'register'])->middleware('throttle:auth');
Route::post('/auth/login', [AutentikasiController::class, 'login'])->middleware('throttle:auth');

// Katalog layanan publik
Route::prefix('layanan')->group(function () {
    Route::get('/', [LayananController::class, 'index']);               // daftar semua layanan
    Route::get('/{layanan}', [LayananController::class, 'show']);       // detail satu layanan
    Route::get('/kategori/{kategori}', [LayananController::class, 'filterByCategory']); // filter by kategori
});

// Galeri publik
Route::prefix('galeri')->group(function () {
    Route::get('/', [GaleriController::class, 'index']);             // semua karya galeri
    Route::get('/kategori', [GaleriController::class, 'categories']); // daftar kategori
    Route::get('/{kategori}/jenis', [GaleriController::class, 'jenisList']); // filter by kategori
});

// Testimoni/Rating publik
Route::get('/rating', [PenilaianController::class, 'index']);

// Kuota booking publik (landing page calendar) — read-only, tanpa auth
Route::prefix('booking')->group(function () {
    Route::get('/quota-today', [ReservasiPublikController::class, 'getTodayQuota'])->middleware('throttle:api');
    Route::get('/quota/{date}', [ReservasiPublikController::class, 'checkQuota'])->middleware('throttle:api');
    Route::get('/quota-month/{year}/{month}', [ReservasiPublikController::class, 'getMonthQuota'])->middleware('throttle:api');
    // Detail slot per hari: kuota + daftar booking anonim (dipakai kalender dashboard)
    Route::get('/day-detail/{date}', [ReservasiPublikController::class, 'getDayDetail'])->middleware('throttle:api');
});

// Master Kendaraan & Add-ons publik (untuk formulir booking & estimasi harga)
Route::prefix('vehicles')->group(function () {
    Route::get('/brands', function (\Illuminate\Http\Request $request) {
        $type = $request->get('type');
        $cacheKey = 'api_vehicle_brands_' . ($type ?: 'all');
        $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($type) {
            $query = \App\Models\VehicleBrand::where('is_active', true);
            if ($type) {
                $query->where('vehicle_type', $type);
            }
            return $query->orderBy('name')->get();
        });
        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    });

    Route::get('/models/{brandId}', function ($brandId) {
        $cacheKey = 'api_vehicle_models_' . (int) $brandId;
        $models = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($brandId) {
            return \App\Models\VehicleModel::where('brand_id', $brandId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        });
        return response()->json([
            'status' => 'success',
            'data'   => $models,
        ]);
    });
});

Route::get('/addons', function (\Illuminate\Http\Request $request) {
    $category = $request->get('category');
    $cacheKey = 'api_addons_' . ($category ?: 'all');
    $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($category) {
        $query = \App\Models\Addon::where('is_active', true);
        if ($category && $category !== 'all') {
            $query->whereIn('category', [$category, 'all']);
        }
        return $query->orderBy('name')->get();
    });
    return response()->json([
        'status' => 'success',
        'data'   => $data,
    ]);
});



// ============================================
//  PROTECTED ROUTES — Wajib login (Sanctum)
// ============================================
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    // Auth: logout & profil
    Route::post('/auth/logout', [AutentikasiController::class, 'logout']);
    Route::get('/auth/me', [AutentikasiController::class, 'me']);

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
    //  BOOKING — Fitur booking user
    // ========================================
    Route::prefix('booking')->middleware('throttle:booking')->group(function () {
        Route::get('/', [ReservasiController::class, 'index']);                       // daftar booking user
        Route::post('/', [ReservasiController::class, 'store']);                      // buat booking (3x/menit)
        Route::get('/{id}', [ReservasiController::class, 'show']);                    // detail booking
        Route::post('/{id}/upload-bukti', [ReservasiController::class, 'uploadBukti']); // upload bukti (3x/menit)
        Route::post('/{id}/cancel', [ReservasiController::class, 'cancel']);          // batalkan booking
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
    //  RATING
    // ========================================
    Route::middleware('api.verified')->group(function () {
        Route::get('/rating/saya', [PenilaianController::class, 'myRatings']);
        // [DISABLED] Alur 2 — rating via dropdown layanan tanpa pesanan. Non-aktif.
        // Route::post('/rating/layanan', [RatingController::class, 'storeByLayanan']);
        Route::post('/pesanan/{pesanan}/rating', [PenilaianController::class, 'storeByPesanan']);
    });

    // ========================================
    //  ADMIN ONLY — Manajemen & Laporan
    // ========================================
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Manajemen pesanan (admin bisa lihat & edit semua pesanan)
        Route::prefix('pesanan')->group(function () {
            Route::get('/', [AdminPesananController::class, 'index']);
            Route::get('/{pesanan}', [AdminPesananController::class, 'show']);
            Route::put('/{pesanan}/status', [AdminPesananController::class, 'updateStatus']);
            Route::post('/{pesanan}/note', [AdminPesananController::class, 'addNote']);
        });

        // Manajemen booking (verifikasi, konfirmasi, dll)
        Route::prefix('booking')->group(function () {
            Route::get('/', [AdminReservasiController::class, 'index']);
            Route::get('/{id}', [AdminReservasiController::class, 'show']);
            Route::post('/{booking}/confirm', [AdminReservasiController::class, 'confirm']);
            Route::post('/{booking}/reject', [AdminReservasiController::class, 'reject']);
            Route::post('/{booking}/start', [AdminReservasiController::class, 'start']);
            Route::post('/{booking}/complete', [AdminReservasiController::class, 'complete']);

            // Verifikasi pembayaran booking
            Route::prefix('payment')->group(function () {
                Route::post('/{bookingPayment}/verify', [AdminReservasiController::class, 'verifyPayment']);
                Route::post('/{bookingPayment}/reject', [AdminReservasiController::class, 'rejectPayment']);
            });
        });

        // Verifikasi pembayaran
        Route::prefix('pembayaran')->group(function () {
            Route::get('/', [AdminPembayaranController::class, 'index']);
            Route::put('/{pembayaran}/verify', [AdminPembayaranController::class, 'verify']);
            Route::put('/{pembayaran}/reject', [AdminPembayaranController::class, 'reject']);
        });

        // Statistik & grafik dashboard admin
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStats']);
        Route::get('/dashboard/chart-data', [AdminDashboardController::class, 'getChartData']);
    });
});

// Health check endpoint — monitoring sederhana
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});
