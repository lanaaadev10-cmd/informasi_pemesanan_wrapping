<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaleriController;

// [TAMBAHAN FUNGSI] Proteksi Keamanan: Membatasi user agar tidak refresh berlebihan (Maks. 60x dalam 5 menit)
Route::middleware('throttle:60,5')->group(function () {

    // rute ke publik
    // [KEAMANAN] Endpoint metrics hanya bisa diakses dari localhost
    Route::get('/metrics', function () {
        $allowedIps = ['127.0.0.1', '::1'];
        if (!in_array(request()->ip(), $allowedIps)) {
            abort(403, 'Akses ditolak.');
        }
        return response("# HELP app_status Application status\n# TYPE app_status gauge\napp_status 1\n", 200)
            ->header('Content-Type', 'text/plain; version=0.0.4');
    });

    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/profil-perusahaan', [DashboardController::class, 'profile'])->name('profil.perusahaan');
    Route::get('/tentang-kami', [DashboardController::class, 'tentangKami'])->name('tentang-kami');
    Route::get('/layanan', [DashboardController::class, 'layanan'])->name('layanan');
    Route::get('/galeri-karya', [GaleriController::class, 'index'])->name('galeri.user');
    Route::get('/katalog-layanan', [CustomerController::class, 'katalog'])->name('katalog.user');

    // [TAMBAHAN] Logout via GET untuk menghindari Error 419 Page Expired
    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout.get');

    // --- RUTE TERPROTEKSI (AUTH + ROLE) ---
    Route::middleware(['auth', 'verified'])->group(function () {
        
        // Dashboard & Profile (Hanya admin/user)
        Route::middleware('role:admin|user')->group(function () {
            Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
            
            Route::controller(ProfileController::class)->group(function () {
                Route::get('/profile', 'edit')->name('profile.edit');
                Route::patch('/profile', 'update')->name('profile.update');
                Route::delete('/profile', 'destroy')->name('profile.destroy');
            });

            // Laporan Admin
            Route::get('/admin/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('admin.laporan');
        });

        // --- RUTE KERANJANG ---
        Route::prefix('keranjang')->group(function () {
            Route::get('/', [\App\Http\Controllers\KeranjangController::class, 'index'])->name('keranjang.index');
            Route::post('/tambah', [\App\Http\Controllers\KeranjangController::class, 'tambah'])->name('keranjang.tambah');
            Route::patch('/update/{id}', [\App\Http\Controllers\KeranjangController::class, 'update'])->name('keranjang.update');
            Route::delete('/hapus/{id}', [\App\Http\Controllers\KeranjangController::class, 'hapus'])->name('keranjang.hapus');
            Route::delete('/kosongkan', [\App\Http\Controllers\KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');
        });

        // --- RUTE PESANAN ---
        Route::prefix('pesanan')->group(function () {
            Route::get('/', [\App\Http\Controllers\PesananController::class, 'index'])->name('pesanan.index');
            Route::get('/checkout', function() {
                $keranjang = \App\Models\Keranjang::where('id_user', auth()->id())
                    ->where('status', 'active')
                    ->first();
                
                if (!$keranjang || $keranjang->details->count() == 0) {
                    return redirect()->route('katalog.user')->with('error', 'Keranjang Anda kosong. Silakan pilih paket terlebih dahulu.');
                }
                
                return view('customer.pesanan.checkout', compact('keranjang'));
            })->name('pesanan.checkout.form');
            Route::post('/checkout', [\App\Http\Controllers\PesananController::class, 'checkout'])->name('pesanan.checkout.store');
            Route::get('/{id}', [\App\Http\Controllers\PesananController::class, 'show'])->name('pesanan.show');
            Route::get('/{id}/invoice', [\App\Http\Controllers\PesananController::class, 'invoice'])->name('pesanan.invoice');
            Route::post('/{id}/upload-bukti', [\App\Http\Controllers\PesananController::class, 'uploadBukti'])->name('pesanan.upload-bukti');
        });
    });

});

// --- RUTE API (DENGAN THROTTLE BERBEDA) ---
Route::middleware(['auth', 'throttle:60,1'])->prefix('api')->group(function () {
    // Keranjang API
    Route::get('/keranjang', [\App\Http\Controllers\Api\KeranjangApiController::class, 'index'])->name('api.keranjang.index');
    Route::post('/keranjang/tambah', [\App\Http\Controllers\Api\KeranjangApiController::class, 'tambah'])->name('api.keranjang.tambah');
    Route::patch('/keranjang/{id}', [\App\Http\Controllers\Api\KeranjangApiController::class, 'update'])->name('api.keranjang.update');
    Route::delete('/keranjang/{id}', [\App\Http\Controllers\Api\KeranjangApiController::class, 'hapus'])->name('api.keranjang.hapus');
    Route::delete('/keranjang', [\App\Http\Controllers\Api\KeranjangApiController::class, 'kosongkan'])->name('api.keranjang.kosongkan');

    // Layanan API (Katalog)
    Route::get('/layanans', [\App\Http\Controllers\Api\LayananApiController::class, 'index'])->name('api.layanans.index');
    Route::get('/layanans/categories', [\App\Http\Controllers\Api\LayananApiController::class, 'categories'])->name('api.layanans.categories');
    Route::get('/layanans/{id}', [\App\Http\Controllers\Api\LayananApiController::class, 'show'])->name('api.layanans.show');
    Route::get('/layanans/kategori/{kategori}', [\App\Http\Controllers\Api\LayananApiController::class, 'byCategory'])->name('api.layanans.byCategory');

    // Pesanan API
    Route::get('/pesanan', [\App\Http\Controllers\Api\PesananApiController::class, 'index'])->name('api.pesanan.index');
    Route::post('/pesanan', [\App\Http\Controllers\Api\PesananApiController::class, 'store'])->name('api.pesanan.store');
    Route::get('/pesanan/{id}', [\App\Http\Controllers\Api\PesananApiController::class, 'show'])->name('api.pesanan.show');
    Route::get('/pesanan/{id}/status', [\App\Http\Controllers\Api\PesananApiController::class, 'status'])->name('api.pesanan.status');
    Route::post('/pesanan/{id}/upload-bukti', [\App\Http\Controllers\Api\PesananApiController::class, 'uploadBukti'])->name('api.pesanan.uploadBukti');
    Route::get('/pesanan/{id}/timeline', [\App\Http\Controllers\Api\PesananApiController::class, 'timeline'])->name('api.pesanan.timeline');

    // Pembayaran API
    Route::get('/pembayaran/{pesanan_id}', [\App\Http\Controllers\Api\PembayaranApiController::class, 'show'])->name('api.pembayaran.show');
    Route::post('/pembayaran/{pesanan_id}/verify', [\App\Http\Controllers\Api\PembayaranApiController::class, 'verify'])->name('api.pembayaran.verify');
    Route::get('/pembayaran/methods', [\App\Http\Controllers\Api\PembayaranApiController::class, 'methods'])->name('api.pembayaran.methods');

    // User Profile API
    Route::get('/user/profile', [\App\Http\Controllers\Api\UserProfileApiController::class, 'show'])->name('api.user.profile.show');
    Route::patch('/user/profile', [\App\Http\Controllers\Api\UserProfileApiController::class, 'update'])->name('api.user.profile.update');
    Route::patch('/user/password', [\App\Http\Controllers\Api\UserProfileApiController::class, 'updatePassword'])->name('api.user.password.update');
    Route::delete('/user/profile', [\App\Http\Controllers\Api\UserProfileApiController::class, 'destroy'])->name('api.user.profile.destroy');
    Route::get('/user/dashboard/stats', [\App\Http\Controllers\Api\UserProfileApiController::class, 'dashboardStats'])->name('api.user.dashboard.stats');

    // Notifikasi API
    Route::get('/notifikasi', [\App\Http\Controllers\Api\NotifikasiApiController::class, 'index'])->name('api.notifikasi.index');
    Route::get('/notifikasi/unread', [\App\Http\Controllers\Api\NotifikasiApiController::class, 'unread'])->name('api.notifikasi.unread');
    Route::get('/notifikasi/{id}', [\App\Http\Controllers\Api\NotifikasiApiController::class, 'show'])->name('api.notifikasi.show');
    Route::patch('/notifikasi/{id}', [\App\Http\Controllers\Api\NotifikasiApiController::class, 'markAsRead'])->name('api.notifikasi.markAsRead');
    Route::patch('/notifikasi/mark-all-read', [\App\Http\Controllers\Api\NotifikasiApiController::class, 'markAllAsRead'])->name('api.notifikasi.markAllAsRead');
    Route::delete('/notifikasi/{id}', [\App\Http\Controllers\Api\NotifikasiApiController::class, 'destroy'])->name('api.notifikasi.destroy');
    Route::delete('/notifikasi', [\App\Http\Controllers\Api\NotifikasiApiController::class, 'deleteAll'])->name('api.notifikasi.deleteAll');
});

require __DIR__.'/auth.php';