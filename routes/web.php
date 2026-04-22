<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Halaman utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// Grup Route yang butuh Login (Auth)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (pakai controller)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fitur Katalog
    Route::get('/katalog', [CustomerController::class, 'katalog'])->name('katalog');

    // Manajemen Profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

});

require __DIR__.'/auth.php';