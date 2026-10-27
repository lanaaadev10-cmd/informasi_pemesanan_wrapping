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
            static $profil = null;
            static $cartCounts = [];

            if ($profil === null) {
                $profil = \Illuminate\Support\Facades\Cache::remember(\App\Services\CacheService::SITE_PROFIL_SETTINGS, \App\Services\CacheService::TTL_ONE_DAY, function () {
                    $obj = new \stdClass();

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
                                $obj->{$key} = $value;
                            }
                        } catch (\Exception $e) {
                            // skip if settings not available
                        }
                    }

                    // Computed properties
                    if (!empty($obj->nomor_telepon)) {
                        $nomor = preg_replace('/[^0-9]/', '', $obj->nomor_telepon);
                        if (str_starts_with($nomor, '0')) {
                            $nomor = '62' . substr($nomor, 1);
                        }
                        $obj->whatsapp_link = "https://wa.me/{$nomor}";
                    } else {
                        $obj->whatsapp_link = \App\Helpers\StaticContent::COMPANY_WHATSAPP;
                    }

                    $obj->whatsapp_jasa_url = \App\Helpers\WhatsAppHelper::getJasaUrl();
                    $obj->whatsapp_custom_url = \App\Helpers\WhatsAppHelper::getCustomUrl();

                    return $obj;
                });
            }

            $view->with('profil', $profil);

            // Jumlah item keranjang aktif untuk badge (hanya untuk user login, memoized per request)
            $userId = auth()->id();
            if ($userId) {
                if (!array_key_exists($userId, $cartCounts)) {
                    $cartCounts[$userId] = \App\Models\Keranjang::where('id_user', $userId)
                        ->where('status', 'active')
                        ->withCount('details')
                        ->first()?->details_count ?? 0;
                }
                $view->with('cartCount', $cartCounts[$userId]);
            } else {
                $view->with('cartCount', 0);
            }
        });
    }
}
