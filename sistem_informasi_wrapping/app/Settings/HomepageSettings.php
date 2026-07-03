<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomepageSettings extends Settings
{
    public ?string $home_hero_image = null;
    public string $home_badge_text = '';
    public string $home_hero_title_line1 = '';
    public string $home_hero_title_line2 = '';
    public string $home_subtitle = '';
    public string $home_stat1_value = '';
    public string $home_stat1_label = '';
    public string $home_stat2_value = '';
    public string $home_stat2_label = '';
    public string $home_keunggulan_card1_title = '';
    public string $home_keunggulan_card1_desc = '';
    public string $home_keunggulan_card2_title = '';
    public string $home_keunggulan_card2_desc = '';
    public string $home_keunggulan_card3_title = '';
    public string $home_keunggulan_card3_desc = '';
    public string $home_keunggulan_card4_title = '';
    public string $home_keunggulan_card4_desc = '';
    public string $home_cta_title = '';
    public string $home_cta_subtitle = '';
    public string $home_step1_title = '';
    public string $home_step1_desc = '';
    public string $home_step2_title = '';
    public string $home_step2_desc = '';
    public string $home_step3_title = '';
    public string $home_step3_desc = '';
    public string $home_hero_cta_primary = '';
    public string $home_hero_cta_secondary = '';
    public string $home_section_keunggulan_badge = '';
    public string $home_section_keunggulan_title = '';
    public string $home_card_selengkapnya = '';
    public string $home_section_portofolio_badge = '';
    public string $home_section_portofolio_title = '';
    public string $home_section_portofolio_desc = '';
    public string $home_portofolio_lihat_semua = '';
    public string $home_cta_langkah_badge = '';
    public string $home_cta_langkah_tagline = '';
    public string $home_cta_wa_button = '';
    public string $home_cta_pelajari_button = '';
    public string $home_cta_cek_syarat = '';
    public string $home_section_kontak_badge = '';
    public string $home_section_kontak_title = '';
    public string $home_section_kontak_desc = '';
    public string $home_kontak_alamat_label = '';
    public string $home_kontak_telepon_label = '';
    public string $home_kontak_email_label = '';
    public string $home_kontak_jam_label = '';
    public string $home_kontak_no_map = '';

    public static function group(): string
    {
        return 'homepage';
    }
}

