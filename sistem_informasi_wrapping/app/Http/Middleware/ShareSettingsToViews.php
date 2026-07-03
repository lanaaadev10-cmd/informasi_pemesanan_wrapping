<?php

namespace App\Http\Middleware;

use App\Services\CacheService;
use App\Settings\CompanySettings;
use App\Settings\ContentSettings;
use App\Settings\DashboardCustomerSettings;
use App\Settings\GaleriSettings;
use App\Settings\HomepageSettings;
use App\Settings\KeranjangCheckoutSettings;
use App\Settings\LayananSettings;
use App\Settings\PesananSettings;
use App\Settings\ProfilPageSettings;
use App\Settings\TentangKamiSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareSettingsToViews
{
    public function handle(Request $request, Closure $next): Response
    {
        $profil = new \stdClass();

        $map = [
            'company' => CompanySettings::class,
            'homepage' => HomepageSettings::class,
            'layanan' => LayananSettings::class,
            'galeri' => GaleriSettings::class,
            'tentang_kami' => TentangKamiSettings::class,
            'pesanan' => PesananSettings::class,
            'keranjang_checkout' => KeranjangCheckoutSettings::class,
            'dashboard_customer' => DashboardCustomerSettings::class,
            'profil_page' => ProfilPageSettings::class,
            'content' => ContentSettings::class,
        ];

        foreach ($map as $group => $class) {
            try {
                $settings = CacheService::getSettings($group, $class);
                foreach ($settings->toArray() as $key => $value) {
                    $profil->{$key} = $value;
                }
            } catch (\Exception $e) {
                report($e);
            }
        }

        if (!empty($profil->nomor_telepon)) {
            $nomor = preg_replace('/[^0-9]/', '', $profil->nomor_telepon);
            if (str_starts_with($nomor, '0')) {
                $nomor = '62' . substr($nomor, 1);
            }
            $profil->whatsapp_link = "https://wa.me/{$nomor}";
        } else {
            $profil->whatsapp_link = '#';
        }

        View::share('profil', $profil);

        return $next($request);
    }
}
