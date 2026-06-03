<?php

namespace Database\Seeders;

use App\Settings\BerandaSettings;
use App\Settings\CompanySettings;
use App\Settings\ContentSettings;
use App\Settings\DashboardCustomerSettings;
use App\Settings\GaleriSettings;
use App\Settings\HomepageSettings;
use App\Settings\KatalogSettings;
use App\Settings\KeranjangCheckoutSettings;
use App\Settings\LayananSettings;
use App\Settings\LayoutSettings;
use App\Settings\PesananSettings;
use App\Settings\ProfilPageSettings;
use App\Settings\TentangKamiSettings;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $settingsClasses = [
            BerandaSettings::class,
            CompanySettings::class,
            ContentSettings::class,
            DashboardCustomerSettings::class,
            GaleriSettings::class,
            HomepageSettings::class,
            KatalogSettings::class,
            KeranjangCheckoutSettings::class,
            LayananSettings::class,
            LayoutSettings::class,
            PesananSettings::class,
            ProfilPageSettings::class,
            TentangKamiSettings::class,
        ];

        foreach ($settingsClasses as $settingsClass) {
            try {
                $settings = app($settingsClass);
                $settings->save();
            } catch (\Exception $e) {
                echo "Could not initialize {$settingsClass}: " . $e->getMessage() . "\n";
            }
        }
    }
}
