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
        // Share company profile globally with all views
        if (class_exists(\App\Models\ProfilPerusahaan::class)) {
            view()->composer('*', function ($view) {
                $profil = null;
                try {
                    $profil = \App\Models\ProfilPerusahaan::singleton();
                } catch (\Throwable $e) {
                    // Fail silently if DB is not migrated yet
                }
                $view->with('profil', $profil);
            });
        }
    }
}
