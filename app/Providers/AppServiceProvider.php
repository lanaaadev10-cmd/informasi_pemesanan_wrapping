<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\KeranjangService;
use App\Services\PesananService;
use App\Services\PembayaranService;
use App\Services\NotifikasiService;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share company profile globally with all views
        if (class_exists(\App\Models\ProfilPerusahaan::class)) {
            view()->composer('*', function ($view) {
                $profil = null;
                try {
                    $profil = \Illuminate\Support\Facades\Cache::rememberForever('site_profile', function() {
                        return \App\Models\ProfilPerusahaan::first() ?? new \App\Models\ProfilPerusahaan();
                    });
                } catch (\Throwable $e) {
                    // Fail silently if DB is not migrated yet
                }
                $view->with('profil', $profil);
            });
        }
    }
}
