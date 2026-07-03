<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LayananSettings extends Settings
{
    public ?string $layanan_hero_title = null;
    public ?string $layanan_hero_desc = null;
    public ?string $layanan_hero_image = null;
    public ?string $layanan_1_nama = null;
    public ?string $layanan_1_deskripsi = null;
    public ?string $layanan_1_harga = null;
    public ?array $layanan_1_fitur = null;
    public ?string $layanan_1_gambar = null;
    public ?string $layanan_2_nama = null;
    public ?string $layanan_2_deskripsi = null;
    public ?string $layanan_2_harga = null;
    public ?array $layanan_2_fitur = null;
    public ?string $layanan_2_gambar = null;
    public ?string $layanan_3_nama = null;
    public ?string $layanan_3_deskripsi = null;
    public ?string $layanan_3_harga = null;
    public ?array $layanan_3_fitur = null;
    public ?string $layanan_3_gambar = null;
    public ?string $layanan_4_nama = null;
    public ?string $layanan_4_deskripsi = null;
    public ?string $layanan_4_harga = null;
    public ?array $layanan_4_fitur = null;
    public ?string $layanan_4_gambar = null;
    public ?string $layanan_garansi_title = null;
    public ?string $layanan_garansi_desc = null;
    public ?string $layanan_cta_title = null;
    public ?string $layanan_cta_desc = null;
    public ?string $layanan_section_mengapa_badge = null;
    public ?string $layanan_section_mengapa_title = null;
    public ?string $layanan_section_mengapa_desc = null;
    public ?string $layanan_benefit_1_label = null;
    public ?string $layanan_benefit_2_label = null;
    public ?string $layanan_benefit_3_label = null;
    public ?string $layanan_card_pesan_button = null;
    public ?string $layanan_harga_satuan_label = null;
    public ?string $layanan_empty_state_title = null;
    public ?string $layanan_empty_state_desc = null;
    public ?string $layanan_badge_1 = null;
    public ?string $layanan_badge_2 = null;
    public ?string $layanan_badge_3 = null;
    public ?string $layanan_badge_4 = null;
    public ?string $layanan_feature_1_title = null;
    public ?string $layanan_feature_1_desc = null;
    public ?string $layanan_feature_2_title = null;
    public ?string $layanan_feature_2_desc = null;
    public ?string $layanan_feature_3_title = null;
    public ?string $layanan_feature_3_desc = null;
    public ?string $layanan_feature_4_title = null;
    public ?string $layanan_feature_4_desc = null;

    public static function group(): string
    {
        return 'layanan';
    }
}
