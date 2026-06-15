<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CompanySettings extends Settings
{
    // Informasi Perusahaan
    public ?string $nama_perusahaan = null;
    public ?string $deskripsi = null;
    public ?string $alamat = null;
    public ?string $email = null;
    public ?string $nomor_telepon = null;
    public ?string $logo = null;
    public ?string $maps_url = null;
    public ?string $jam_operasional = null;
    public ?string $instagram_url = null;
    public ?string $facebook_url = null;
    public ?string $tiktok_url = null;
    public ?string $whatsapp_url = null;

    public static function group(): string
    {
        return 'company';
    }
}
