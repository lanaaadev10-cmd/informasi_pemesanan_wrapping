<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\KeranjangService;
use App\Services\PesananService;
use App\Services\PembayaranService;
use App\Services\NotifikasiService;
use App\Services\BookingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Keranjang Service
        $this->app->singleton(KeranjangService::class, function ($app) {
            return new KeranjangService();
        });

        // Register Pesanan Service (depends on KeranjangService)
        $this->app->singleton(PesananService::class, function ($app) {
            return new PesananService($app->make(KeranjangService::class));
        });

        // Register Pembayaran Service (depends on PesananService)
        $this->app->singleton(PembayaranService::class, function ($app) {
            return new PembayaranService($app->make(PesananService::class));
        });

        // Register Notifikasi Service
        $this->app->singleton(NotifikasiService::class, function ($app) {
            return new NotifikasiService();
        });

        // Register Booking Service (depends on NotifikasiService)
        $this->app->singleton(BookingService::class, function ($app) {
            return new BookingService($app->make(NotifikasiService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Settings cache will be cleared manually in Filament pages
    }
}
