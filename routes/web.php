<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


//  
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


// // Halaman utama (Landing Page)
// Route::get('/', function () {
//     return view('welcome');
// });

// Halaman utama (Landing Page) dengan data dari DashboardController
Route::get('/', [DashboardController::class, 'index']);


// Grup Route yang butuh Login (Auth)
Route::middleware(['auth', 'verified'])->group(function () {


// Dashboard Customer
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('dashboard');


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