<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan data profil perusahaan ke semua view agar tidak error
        \Illuminate\Support\Facades\View::share('profil', \App\Models\ProfilPerusahaan::first());
        
        // Paksa semua URL (termasuk form action login) menggunakan HTTPS untuk Ngrok
        if (request()->headers->has('X-Forwarded-Proto') || env('APP_ENV') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
