<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaleriController;

// Baru saya tambah: Komentar pemisah rute publik
// --- RUTE PUBLIK ---
Route::get('/metrics', function () {
    return response("# HELP app_status Application status\n# TYPE app_status gauge\napp_status 1\n", 200)
        ->header('Content-Type', 'text/plain; version=0.0.4');
});

Route::get('/', [DashboardController::class, 'index'])->name('home');

// Baru saya tambah: Rute untuk halaman profil perusahaan versi modern (Frontend)
Route::get('/profil-perusahaan', [DashboardController::class, 'profile'])->name('profil.perusahaan');


// --- RUTE LOGIN (AUTH) ---
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

    Route::get('/katalog', [CustomerController::class, 'katalog'])->name('katalog');

    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

});

require __DIR__.'/auth.php';