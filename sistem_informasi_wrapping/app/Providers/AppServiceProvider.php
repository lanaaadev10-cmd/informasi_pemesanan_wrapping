<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\KeranjangService;
use App\Services\PesananService;
use App\Services\PembayaranService;
use App\Services\NotifikasiService;

use Spatie\LaravelSettings\Models\SettingsModel;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(KeranjangService::class, function ($app) {
            return new KeranjangService();
        });

        $this->app->singleton(PesananService::class, function ($app) {
            return new PesananService($app->make(KeranjangService::class));
        });

        $this->app->singleton(PembayaranService::class, function ($app) {
            return new PembayaranService($app->make(PesananService::class));
        });

        $this->app->singleton(NotifikasiService::class, function ($app) {
            return new NotifikasiService();
        });
    }

    public function boot(): void
    {
    }
}
