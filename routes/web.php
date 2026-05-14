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
    Route::get('/keranjang', [\App\Http\Controllers\Api\KeranjangApiController::class, 'index']);
    Route::post('/keranjang/tambah', [\App\Http\Controllers\Api\KeranjangApiController::class, 'tambah']);
    Route::delete('/keranjang/{id}', [\App\Http\Controllers\Api\KeranjangApiController::class, 'hapus']);
    
    // Notifikasi Polling
    Route::get('/notifikasi/unread', function() {
        $notifs = \App\Models\Notifikasi::where('id_user', auth()->id())
            ->where('is_read', false)
            ->get();
        
        // Mark as read immediately to avoid duplicate toasts
        \App\Models\Notifikasi::where('id_user', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return response()->json($notifs);
    });
});

require __DIR__.'/auth.php';