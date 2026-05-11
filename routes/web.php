<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GaleriController;

// Route untuk Metrics (Monitoring)
Route::get('/metrics', function () {
    return response("
# HELP app_status Application status
# TYPE app_status gauge
app_status 1

# HELP memory_usage Memory usage in bytes
# TYPE memory_usage gauge
memory_usage " . memory_get_usage()
    , 200)
    ->header('Content-Type', 'text/plain; version=0.0.4');
});

// --- PERBAIKAN DI SINI ---
// Gunakan DashboardController untuk halaman utama agar semua variabel ($galeris, $layanans, $profil) terisi
Route::get('/', [DashboardController::class, 'index'])->name('home');


// Grup Route yang butuh Login (Auth)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Customer
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

    // Fitur Katalog
    Route::get('/katalog', [CustomerController::class, 'katalog'])->name('katalog');

    // Fitur galeri (Halaman internal)
    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');

    // Manajemen Profile User
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

});

require __DIR__.'/auth.php';