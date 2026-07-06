<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomepageSettings extends Settings
{
    public ?string $home_title = null;
    public ?string $home_hero_image = null;
    public ?string $home_feature_title = null;
    public ?string $home_feature_subtitle = null;
    public ?string $home_badge_text = null;
    public ?string $home_hero_title_line1 = null;
    public ?string $home_hero_title_line2 = null;
    public ?string $home_subtitle = null;
    public ?string $home_stat1_value = null;
    public ?string $home_stat1_label = null;
    public ?string $home_stat2_value = null;
    public ?string $home_stat2_label = null;
    public ?string $home_keunggulan_card1_title = null;
    public ?string $home_keunggulan_card1_desc = null;
    public ?string $home_keunggulan_card2_title = null;
    public ?string $home_keunggulan_card2_desc = null;
    public ?string $home_keunggulan_card3_title = null;
    public ?string $home_keunggulan_card3_desc = null;
    public ?string $home_keunggulan_card4_title = null;
    public ?string $home_keunggulan_card4_desc = null;
    public ?string $home_cta_title = null;
    public ?string $home_cta_subtitle = null;
    public ?string $home_step1_title = null;
    public ?string $home_step1_desc = null;
    public ?string $home_step2_title = null;
    public ?string $home_step2_desc = null;
    public ?string $home_step3_title = null;
    public ?string $home_step3_desc = null;
    public ?string $home_hero_cta_primary = null;
    public ?string $home_hero_cta_secondary = null;
    public ?string $home_section_keunggulan_badge = null;
    public ?string $home_section_keunggulan_title = null;
    public ?string $home_card_selengkapnya = null;
    public ?string $home_section_portofolio_badge = null;
    public ?string $home_section_portofolio_title = null;
    public ?string $home_section_portofolio_desc = null;
    public ?string $home_portofolio_lihat_semua = null;
    public ?string $home_cta_langkah_badge = null;
    public ?string $home_cta_langkah_tagline = null;
    public ?string $home_cta_wa_button = null;
    public ?string $home_cta_pelajari_button = null;

    public static function group(): string
    {
        return 'homepage';
    }
}
