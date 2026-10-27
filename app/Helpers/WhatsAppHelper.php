<?php

namespace App\Helpers;

use App\Settings\CompanySettings;

class WhatsAppHelper
{
    const PESAN_JASA = 'Halo Admin, saya ingin berkonsultasi dan melakukan reservasi jasa wrapping kendaraan. Mohon informasi lebih lanjut mengenai kebutuhan dan proses pemesanannya.';
    const PESAN_CUSTOM = 'Halo Admin, saya ingin berkonsultasi mengenai wrapping custom kendaraan. Saya ingin menjelaskan kebutuhan desain dan spesifikasi terlebih dahulu.';

    /**
     * Dapatkan nomor WhatsApp admin terpusat.
     * Prioritas:
     * 1. Pengaturan CompanySettings (nomor_telepon yang dapat dikelola via panel)
     * 2. Config services.whatsapp.admin_phone
     * 3. StaticContent::COMPANY_PHONE
     */
    public static function getAdminPhone(): string
    {
        try {
            $company = app(CompanySettings::class);
            if (!empty($company->nomor_telepon)) {
                $clean = preg_replace('/[^0-9]/', '', $company->nomor_telepon);
                if (str_starts_with($clean, '0')) {
                    $clean = '62' . substr($clean, 1);
                }
                if (!empty($clean)) {
                    return $clean;
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika context test / settings belum siap
        }

        $configPhone = config('services.whatsapp.admin_phone');
        if (!empty($configPhone)) {
            $clean = preg_replace('/[^0-9]/', '', $configPhone);
            if (str_starts_with($clean, '0')) {
                $clean = '62' . substr($clean, 1);
            }
            if (!empty($clean)) {
                return $clean;
            }
        }

        return defined('App\Helpers\StaticContent::COMPANY_PHONE')
            ? StaticContent::COMPANY_PHONE
            : '6283117163605';
    }

    /**
     * URL WhatsApp untuk konsultasi & reservasi Jasa
     */
    public static function getJasaUrl(?string $customMessage = null): string
    {
        $phone = self::getAdminPhone();
        $message = $customMessage ?: (config('services.whatsapp.pesan_jasa') ?: self::PESAN_JASA);
        return "https://api.whatsapp.com/send?phone={$phone}&text=" . rawurlencode($message);
    }

    /**
     * URL WhatsApp untuk konsultasi & reservasi Custom
     */
    public static function getCustomUrl(?string $customMessage = null): string
    {
        $phone = self::getAdminPhone();
        $message = $customMessage ?: (config('services.whatsapp.pesan_custom') ?: self::PESAN_CUSTOM);
        return "https://api.whatsapp.com/send?phone={$phone}&text=" . rawurlencode($message);
    }
}
