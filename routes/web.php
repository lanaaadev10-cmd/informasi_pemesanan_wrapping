<?php

/*
|--------------------------------------------------------------------------
| RUTE WEB — Aplikasi Pemesanan Wrapping
|--------------------------------------------------------------------------
| File ini mendefinisikan semua rute yang bisa diakses via browser.
| Terdiri dari: halaman publik (landing page), halaman terproteksi
| (dashboard customer), serta fitur admin (offline orders & laporan).
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaleriController;
// use App\Http\Controllers\Admin\OfflineOrderController;

/*
 * Middleware throttle:60,5 — Proteksi agar user tidak melakukan
 * refresh berlebihan (maksimal 60 request dalam 5 menit).
 */
Route::middleware('throttle:60,5')->group(function () {

    // ====================================================================
    // RUTE PUBLIK — Landing page & halaman yang bisa diakses siapa saja
    // ====================================================================

    // Endpoint monitoring khusus localhost (cek kesehatan aplikasi)
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
    Route::get('/katalog-layanan', [CustomerController::class, 'katalog'])->name('katalog.user');
    Route::get('/galeri-karya', [GaleriController::class, 'index'])->name('galeri.user');
    Route::get('/galeri/{kategori}', [GaleriController::class, 'kategori'])->name('galeri.kategori');

    // Logout via GET — solusi jika form POST logout mengalami Error 419
    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout.get');

    // ====================================================================
    // RUTE TERPROTEKSI — Wajib login + verifikasi email
    // ====================================================================
    Route::middleware(['auth', 'verified'])->group(function () {

        // Dashboard & Profile — bisa diakses oleh admin maupun user biasa
        Route::middleware('role:admin|user')->group(function () {
            Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

            Route::controller(ProfileController::class)->group(function () {
                Route::get('/profile', 'edit')->name('profile.edit');
                Route::patch('/profile', 'update')->name('profile.update');
                Route::delete('/profile', 'destroy')->name('profile.destroy');
            });

            // Cetak laporan — hanya admin (filter ada di blade)
            Route::get('/admin/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('admin.laporan');

            // Pemesanan Offline — khusus admin untuk membuat pesanan manual
            // tanpa melalui proses checkout online (misal: pelanggan datang langsung)
            // Route::middleware('role:admin')->prefix('admin-offline')->name('admin.offline.')->group(function () {
            //     Route::get('/orders', [OfflineOrderController::class, 'index'])->name('orders.index');
            //     Route::get('/orders/create', [OfflineOrderController::class, 'create'])->name('orders.create');
            //     Route::post('/orders', [OfflineOrderController::class, 'store'])->name('orders.store');
            //     Route::get('/orders/{id}/edit', [OfflineOrderController::class, 'edit'])->name('orders.edit');
            //     Route::put('/orders/{id}', [OfflineOrderController::class, 'update'])->name('orders.update');
            //     Route::delete('/orders/{id}', [OfflineOrderController::class, 'destroy'])->name('orders.destroy');
            // });
        });

        // ================================================================
        // KERANJANG BELANJA
        // ================================================================
        Route::prefix('keranjang')->group(function () {
            Route::get('/', [\App\Http\Controllers\KeranjangController::class, 'index'])->name('keranjang.index');
            Route::post('/tambah', [\App\Http\Controllers\KeranjangController::class, 'tambah'])->name('keranjang.tambah');
            Route::patch('/update/{id}', [\App\Http\Controllers\KeranjangController::class, 'update'])->name('keranjang.update');
            Route::delete('/hapus/{id}', [\App\Http\Controllers\KeranjangController::class, 'hapus'])->name('keranjang.hapus');
            Route::delete('/kosongkan', [\App\Http\Controllers\KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');
        });

        // ================================================================
        // PESANAN / ORDER
        // ================================================================
        Route::prefix('pesanan')->group(function () {
            Route::get('/', [\App\Http\Controllers\PesananController::class, 'index'])->name('pesanan.index');

            // Direct order: pesan langsung dari halaman layanan (skip keranjang)
            Route::get('/buat', function () {
                $packageId = request('package_id');
                if (!$packageId) {
                    return redirect()->route('layanan')->with('error', 'Paket tidak ditemukan.');
                }
                $package = \App\Models\Layanan::findOrFail($packageId);
                return view('dashboard.customer.pesanan.direct-order', compact('package'));
            })->name('pesanan.direct-order');

            // Checkout: proses dari keranjang → form data kendaraan → pembayaran
            Route::get('/checkout', function () {
                $keranjang = \App\Models\Keranjang::where('id_user', auth()->id())
                    ->where('status', 'active')
                    ->first();

                if (!$keranjang || $keranjang->details->count() == 0) {
                    return redirect()->route('layanan')
                        ->with('error', 'Keranjang Anda kosong. Silakan pilih paket terlebih dahulu.');
                }

                return view('dashboard.customer.pesanan.checkout', compact('keranjang'));
            })->name('pesanan.checkout.form');

            Route::post('/checkout', [\App\Http\Controllers\PesananController::class, 'checkout'])->name('pesanan.checkout.store');
            Route::get('/{id}', [\App\Http\Controllers\PesananController::class, 'show'])->name('pesanan.show');
            Route::get('/{id}/invoice', [\App\Http\Controllers\PesananController::class, 'invoice'])->name('pesanan.invoice');
            Route::post('/{id}/upload-bukti', [\App\Http\Controllers\PesananController::class, 'uploadBukti'])->name('pesanan.upload-bukti');
        });
    });
});

// Rute auth default Laravel Breeze (login, register, forgot password, dll)
require __DIR__ . '/auth.php';
