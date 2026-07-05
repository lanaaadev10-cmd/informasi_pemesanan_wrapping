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

    public static function group(): string
    {
        return 'profil_page';
    }
}
