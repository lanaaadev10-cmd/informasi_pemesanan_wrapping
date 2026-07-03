<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ProfilPageSettings extends Settings
{
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $stats_projects = null;
    public ?string $profil_pillar_1_title = null;
    public ?string $profil_pillar_1_desc = null;
    public ?string $profil_pillar_2_title = null;
    public ?string $profil_pillar_2_desc = null;
    public ?string $profil_pillar_3_title = null;
    public ?string $profil_pillar_3_desc = null;
    public ?string $profil_section_badge = null;
    public ?string $profil_section_title = null;
    public ?string $profil_banner_heading = null;
    public ?string $profil_stat_label = null;
    public ?string $profil_stat_desc = null;
    public ?string $profil_section_narrative_badge = null;
    public ?string $profil_section_narrative_title = null;
    public ?string $profil_cta_button = null;
    public ?string $profil_pillar_section_title = null;
    public ?string $profil_global_badge = null;
    public ?string $profil_global_title = null;
    public ?string $profil_global_desc = null;
    public ?string $profil_legal_title_modal = null;
    public ?string $profil_legal_visi_title = null;
    public ?string $profil_legal_misi_title = null;
    public ?string $profil_legal_sejarah_title = null;
    public ?string $profil_hero_image = null;
    public ?string $profil_hero_badge = null;
    public ?string $profil_master_craft_label = null;
    public ?string $profil_badge_1_title = null;
    public ?string $profil_badge_1_desc = null;
    public ?string $profil_badge_2_title = null;
    public ?string $profil_badge_2_desc = null;
    public ?string $profil_narrative_p1 = null;
    public ?string $profil_narrative_p2 = null;
    public ?string $profil_location_1_name = null;
    public ?string $profil_location_1_addr = null;
    public ?string $profil_location_2_name = null;
    public ?string $profil_location_2_addr = null;
    public ?string $profil_modal_button_label = null;
    public ?string $profil_modal_history_title = null;
    public ?string $profil_sejarah_p1 = null;
    public ?string $profil_sejarah_p2 = null;

    public static function group(): string
    {
        return 'profil_page';
    }
}
