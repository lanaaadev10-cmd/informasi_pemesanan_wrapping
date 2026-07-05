<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LayoutSettings extends Settings
{
    public ?string $accent_color = null;
    public ?string $primary_layout = null;
    public ?bool $dark_mode = null;
    public ?int $layanan_grid_columns = null;
    public ?string $layanan_card_style = null;
    public ?bool $layanan_show_benefits = null;
    public ?bool $layanan_show_warranty = null;

    public static function group(): string
    {
        return 'layout';
    }
}
