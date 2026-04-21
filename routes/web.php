<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Halaman utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// Grup Route yang butuh Login (Auth)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Customer
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Fitur Katalog
    Route::get('/katalog', [CustomerController::class, 'katalog'])->name('katalog');

    // Manajemen Profile (Bawaan Breeze)
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';