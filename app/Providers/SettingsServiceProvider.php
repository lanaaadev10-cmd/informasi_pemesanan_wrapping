<?php

namespace App\Providers;

use App\Settings\CompanySettings;
use App\Settings\DashboardCustomerSettings;
use App\Settings\HomepageSettings;
use App\Settings\KatalogSettings;
use App\Settings\KeranjangCheckoutSettings;
use App\Settings\LayoutSettings;
use App\Settings\LayananSettings;
use App\Settings\PesananSettings;
use App\Settings\ProfilPageSettings;
use App\Settings\ContentSettings;
use App\Settings\TentangKamiSettings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $profil = new \stdClass();

            $settingsClasses = [
                CompanySettings::class,
                HomepageSettings::class,
                LayananSettings::class,
                TentangKamiSettings::class,
                KatalogSettings::class,
                PesananSettings::class,
                KeranjangCheckoutSettings::class,
                DashboardCustomerSettings::class,
                ProfilPageSettings::class,
                ContentSettings::class,
                LayoutSettings::class,
            ];

            foreach ($settingsClasses as $settingsClass) {
                try {
                    $settings = app($settingsClass);
                    foreach ($settings->toArray() as $key => $value) {
                        $profil->{$key} = $value;
                    }
                } catch (\Exception $e) {
                    // skip if settings not available
                }
            }

            // Computed properties
            if (!empty($profil->nomor_telepon)) {
                $nomor = preg_replace('/[^0-9]/', '', $profil->nomor_telepon);
                if (str_starts_with($nomor, '0')) {
                    $nomor = '62' . substr($nomor, 1);
                }
                $profil->whatsapp_link = "https://wa.me/{$nomor}";
            } else {
                $profil->whatsapp_link = '#';
            }

            $view->with('profil', $profil);
        });
    }
}
