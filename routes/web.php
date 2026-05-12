<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaleriController;

// [TAMBAHAN FUNGSI] Proteksi Keamanan: Membatasi user agar tidak refresh berlebihan (Maks. 60x dalam 5 menit)
Route::middleware('throttle:60,5')->group(function () {

    // --- RUTE PUBLIK ---
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

    // --- RUTE DASHBOARD (AUTH + ROLE) ---
    Route::middleware(['auth', 'verified', 'role:admin|user'])->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
        });
    });

});

require __DIR__.'/auth.php';