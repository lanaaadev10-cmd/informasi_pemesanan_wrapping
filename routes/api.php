<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Api\NotifikasiController;

/**
 * API v1 Routes
 * 
 * Base URL: /api
 * Authentication: Sanctum Bearer Token
 */

// ========================================
// PUBLIC ROUTES (No Auth Required)
// ========================================

// Auth Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Public Catalog
Route::prefix('layanan')->group(function () {
    Route::get('/', [LayananController::class, 'index']);
    Route::get('/{layanan}', [LayananController::class, 'show']);
    Route::get('/kategori/{kategori}', [LayananController::class, 'filterByCategory']);
});

// ========================================
// PROTECTED ROUTES (Auth Required)
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Shopping Cart
    Route::prefix('keranjang')->group(function () {
        Route::get('/', [KeranjangController::class, 'index']);
        Route::get('/count', [KeranjangController::class, 'getCount']);
        Route::get('/check/{idLayanan}', [KeranjangController::class, 'checkItemInCart']);
        Route::post('/item', [KeranjangController::class, 'addItem']);
        Route::put('/item/{idDetailKeranjang}', [KeranjangController::class, 'updateItem']);
        Route::delete('/item/{idDetailKeranjang}', [KeranjangController::class, 'removeItem']);
        Route::delete('/clear', [KeranjangController::class, 'clear']);
    });

    // Orders
    Route::prefix('pesanan')->group(function () {
        Route::get('/', [PesananController::class, 'index']);
        Route::post('/', [PesananController::class, 'store']);
        Route::get('/{pesanan}', [PesananController::class, 'show']);
        Route::put('/{pesanan}/cancel', [PesananController::class, 'cancel']);
        Route::get('/{pesanan}/invoice', [PesananController::class, 'getInvoice']);

        // Payment for specific order
        Route::prefix('{pesananId}/pembayaran')->group(function () {
            Route::post('/upload', [PembayaranController::class, 'upload']);
            Route::get('/status', [PembayaranController::class, 'getStatus']);
        });
    });

    // Notifications
    Route::prefix('notifikasi')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index']);
        Route::get('/unread-count', [NotifikasiController::class, 'unreadCount']);
        Route::post('/{notifikasi}/read', [NotifikasiController::class, 'markAsRead']);
        Route::delete('/{notifikasi}', [NotifikasiController::class, 'destroy']);
    });

    // ========================================
    // ADMIN ONLY ROUTES
    // ========================================
    
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Admin order management
        Route::prefix('pesanan')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\AdminPesananController::class, 'index']);
            Route::get('/{pesanan}', [\App\Http\Controllers\Api\Admin\AdminPesananController::class, 'show']);
            Route::put('/{pesanan}/status', [\App\Http\Controllers\Api\Admin\AdminPesananController::class, 'updateStatus']);
            Route::post('/{pesanan}/note', [\App\Http\Controllers\Api\Admin\AdminPesananController::class, 'addNote']);
        });

        // Admin payment verification
        Route::prefix('pembayaran')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\AdminPembayaranController::class, 'index']);
            Route::put('/{pembayaran}/verify', [\App\Http\Controllers\Api\Admin\AdminPembayaranController::class, 'verify']);
            Route::put('/{pembayaran}/reject', [\App\Http\Controllers\Api\Admin\AdminPembayaranController::class, 'reject']);
        });

        // Admin dashboard stats
        Route::get('/dashboard/stats', [\App\Http\Controllers\Api\Admin\AdminDashboardController::class, 'getStats']);
        Route::get('/dashboard/chart-data', [\App\Http\Controllers\Api\Admin\AdminDashboardController::class, 'getChartData']);

    });

});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});
