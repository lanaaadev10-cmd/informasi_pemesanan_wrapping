<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TentangKamiSettings extends Settings
{
    public ?string $visi = null;
    public ?string $misi = null;
    public ?string $sejarah = null;
    public ?string $tentang_kami_hero_title = null;
    public ?string $tentang_kami_hero_desc = null;
    public ?string $tentang_kami_hero_image = null;
    public ?string $tentang_kami_team_title = null;
    public ?string $tentang_kami_team_desc = null;
    public ?array $tentang_kami_team_members = null;
    public ?int $tentang_kami_values_columns = null;
    public ?bool $tentang_kami_show_values = null;
    public ?bool $tentang_kami_show_history = null;
    public ?bool $tentang_kami_show_team = null;
    public ?string $tentang_kami_hero_badge = null;
    public ?string $tentang_kami_visi_title = null;
    public ?string $tentang_kami_misi_title = null;
    public ?string $tentang_kami_values_title = null;
    public ?string $tentang_kami_values_1_title = null;
    public ?string $tentang_kami_values_1_desc = null;
    public ?string $tentang_kami_values_2_title = null;
    public ?string $tentang_kami_values_2_desc = null;
    public ?string $tentang_kami_values_3_title = null;
    public ?string $tentang_kami_values_3_desc = null;
    public ?string $tentang_kami_sejarah_badge = null;
    public ?string $tentang_kami_sejarah_title = null;
    public ?string $tentang_kami_tim_badge = null;
    public ?string $tentang_kami_cta_title = null;
    public ?string $tentang_kami_cta_desc = null;
    public ?string $tentang_kami_cta_primary_button = null;
    public ?string $tentang_kami_cta_secondary_button = null;

    public static function group(): string
    {
        return 'tentang_kami';
    }
}
