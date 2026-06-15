<?php

namespace Database\Seeders;

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
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $settingsClasses = [
            CompanySettings::class,
            ContentSettings::class,
            DashboardCustomerSettings::class,
            GaleriSettings::class,
            HomepageSettings::class,
            KeranjangCheckoutSettings::class,
            LayananSettings::class,
            PesananSettings::class,
            ProfilPageSettings::class,
            TentangKamiSettings::class,
        ];

        foreach ($settingsClasses as $settingsClass) {
            try {
                $settings = app($settingsClass);
                $reflection = new \ReflectionClass($settings);
                $defaults = $reflection->getDefaultProperties();

                foreach ($defaults as $key => $value) {
                    $settings->{$key} = $value;
                }

                $settings->save();
            } catch (\Exception $e) {
                echo "Could not initialize {$settingsClass}: " . $e->getMessage() . "\n";
            }
        }
    }
}
