<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GaleriSettings extends Settings
{
    public ?string $galeri_hero_title = null;
    public ?string $galeri_hero_desc = null;
    public ?string $galeri_hero_image = null;
    public ?string $galeri_intro_text = null;
    public ?string $galeri_empty_state_title = null;
    public ?string $galeri_empty_state_desc = null;
    public ?string $galeri_filter_all_label = null;
    public ?array $galeri_filter_categories = null;

    public static function group(): string
    {
        return 'galeri';
    }
}
