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

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

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
        if (! in_array(request()->ip(), $allowedIps)) {
            abort(403, 'Akses ditolak.');
        }

        return response("# HELP app_status Application status\n# TYPE app_status gauge\napp_status 1\n", 200)
            ->header('Content-Type', 'text/plain; version=0.0.4');
    });

    Route::get('/', [LandingPageController::class, 'index'])->name('home');
    Route::get('/profil-perusahaan', [LandingPageController::class, 'profile'])->name('profil.perusahaan');
    Route::get('/tentang-kami', [LandingPageController::class, 'tentangKami'])->name('tentang-kami');
    Route::get('/layanan', [LandingPageController::class, 'layanan'])->name('layanan');
    Route::get('/katalog-layanan', [CustomerController::class, 'katalog'])->name('katalog.user');
    Route::get('/galeri-karya', [GaleriController::class, 'index'])->name('galeri.user');
    Route::get('/galeri/{kategori}', [GaleriController::class, 'kategori'])->name('galeri.kategori');
    Route::get('/kebijakan-privasi', [LandingPageController::class, 'kebijakanPrivasi'])->name('kebijakan-privasi');
    Route::get('/testimoni', [TestimoniController::class, 'index'])->name('testimoni.index');

    // [REMOVED] KALKULATOR WRAPPING & ESTIMASI BIAYA
    // Fitur dinonaktifkan sesuai permintaan pengguna; URL lama dialihkan ke booking.
    Route::redirect('/kalkulator-wrapping', '/booking/buat')->name('kalkulator.index');
    Route::redirect('/admin/login.', '/admin/login');
    Route::post('/admin/login', [\App\Http\Controllers\AdminAuthController::class, 'login'])->name('admin.login.submit');

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
    Route::middleware(['auth'])->group(function () {

        // Dashboard & Profile — bisa diakses oleh admin maupun user biasa
        Route::middleware('role:admin|user')->group(function () {
            Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

            Route::controller(ProfileController::class)->group(function () {
                Route::get('/profile', 'edit')->name('profile.edit');
                Route::patch('/profile', 'update')->name('profile.update');
                Route::delete('/profile', 'destroy')->name('profile.destroy');
            });

            // Cetak laporan — hanya admin (filter ada di blade)
            Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
        });

        // ================================================================
        // HUB TRANSAKSI TERPADU (FASE 1)
        // ================================================================
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

        // ================================================================
        // KERANJANG BELANJA
        // ================================================================
        Route::prefix('keranjang')->group(function () {
            Route::get('/', [KeranjangController::class, 'index'])->name('keranjang.index');
            Route::post('/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
            Route::patch('/update/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
            Route::delete('/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
            Route::delete('/kosongkan', [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');
        });

        // ================================================================
        // PESANAN / ORDER
        // ================================================================
        Route::prefix('pesanan')->group(function () {
            Route::get('/', [PesananController::class, 'index'])->name('pesanan.index');

            // Direct order diarahkan ke Booking Jadwal (Fase Integrasi)
            Route::get('/buat', function () {
                $packageId = request('package_id');
                return redirect()->route('booking.create', $packageId ? ['layanan_id' => $packageId] : []);
            })->name('pesanan.direct-order');

            // Checkout keranjang diarahkan langsung ke Booking Jadwal
            Route::get('/checkout', function () {
                $keranjang = Keranjang::where('id_user', auth()->id())
                    ->where('status', 'active')
                    ->first();
                $packageId = $keranjang?->details?->first()?->id_paket;

                return redirect()->route('booking.create', $packageId ? ['layanan_id' => $packageId] : []);
            })->name('pesanan.checkout.form');

            Route::post('/checkout', [PesananController::class, 'checkout'])->name('pesanan.checkout.store');
            Route::get('/{id}', [PesananController::class, 'show'])->name('pesanan.show');
            Route::get('/{id}/invoice', [PesananController::class, 'invoice'])->name('pesanan.invoice');
            Route::post('/{id}/upload-bukti', [PesananController::class, 'uploadBukti'])->name('pesanan.upload-bukti');

            // Alur 1: Rating per layanan dalam pesanan (hanya status selesai)
            Route::get('/{id}/rating', [RatingController::class, 'form'])->name('pesanan.rating.form');
            Route::post('/{id}/rating', [RatingController::class, 'store'])->name('pesanan.rating.store');
        });

        // [DISABLED] Alur 2 — Rating layanan tanpa pesanan (dropdown layanan).
        // Non-aktif karena tidak relevan; rating hanya melalui Alur 1 (pesanan berstatus selesai).
        // Untuk re-enable: hapus tanda komentar di bawah ini.
        // Route::prefix('rating')->group(function () {
        //     Route::get('/buat', [RatingController::class, 'formLayanan'])->name('rating.layanan.form');
        //     Route::post('/buat', [RatingController::class, 'storeLayanan'])->name('rating.layanan.store');
        // });

        // ================================================================
        // BOOKING — Fitur booking jadwal pengerjaan wrapping
        // ================================================================
        Route::prefix('booking')->name('booking.')->group(function () {
            Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('index');
            Route::get('/buat', [\App\Http\Controllers\BookingController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\BookingController::class, 'store'])
                ->middleware('throttle:3,1')
                ->name('store');
            Route::get('/{id}', [\App\Http\Controllers\BookingController::class, 'show'])->name('show');
            Route::post('/{id}/upload-bukti', [\App\Http\Controllers\BookingController::class, 'uploadBukti'])
                ->middleware('throttle:3,1')
                ->name('upload-bukti');
            Route::post('/{id}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('cancel');
        });
    });
});

// Rute auth default Laravel Breeze (login, register, forgot password, dll)
require __DIR__.'/auth.php';
