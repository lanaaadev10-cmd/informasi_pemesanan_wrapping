<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class KatalogSettings extends Settings
{
    public ?string $katalog_hero_title = null;
    public ?string $katalog_hero_desc = null;
    public ?string $katalog_intro_text = null;
    public ?string $katalog_empty_state_title = null;
    public ?string $katalog_empty_state_desc = null;
    public ?string $katalog_feature_1_title = null;
    public ?string $katalog_feature_1_desc = null;
    public ?string $katalog_feature_2_title = null;
    public ?string $katalog_feature_2_desc = null;
    public ?string $katalog_feature_3_title = null;
    public ?string $katalog_feature_3_desc = null;
    public ?string $katalog_feature_4_title = null;
    public ?string $katalog_feature_4_desc = null;
    public ?string $katalog_filter_all_label = null;
    public ?array $katalog_filter_categories = null;
    public ?string $katalog_card_book_button = null;
    public ?string $katalog_harga_custom_label = null;

    public static function group(): string
    {
        return 'katalog';
    }
}
