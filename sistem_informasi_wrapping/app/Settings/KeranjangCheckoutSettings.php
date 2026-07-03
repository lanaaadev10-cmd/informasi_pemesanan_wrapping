<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class KeranjangCheckoutSettings extends Settings
{
    public ?string $keranjang_hero_text = null;
    public ?string $keranjang_title = null;
    public ?string $keranjang_subtitle = null;
    public ?string $keranjang_empty_title = null;
    public ?string $keranjang_empty_desc = null;
    public ?string $keranjang_warranty_title = null;
    public ?string $keranjang_warranty_desc = null;
    public ?string $keranjang_service_charge_label = null;
    public ?string $keranjang_service_charge_amount = null;
    public ?string $keranjang_checkout_button_label = null;
    public ?string $checkout_step_1_label = null;
    public ?string $checkout_step_2_label = null;
    public ?string $checkout_step_3_label = null;
    public ?string $checkout_step_4_label = null;
    public ?string $checkout_step2_title = null;
    public ?string $checkout_step2_subtitle = null;
    public ?string $checkout_step3_title = null;
    public ?string $checkout_step3_subtitle = null;
    public ?string $checkout_warranty_badge_1 = null;
    public ?string $checkout_warranty_badge_2 = null;
    public ?string $checkout_confirm_button_label = null;
    public ?string $keranjang_section_data_kendaraan_title = null;
    public ?string $checkout_warranty_badge_3 = null;
    public ?string $checkout_estimasi_durasi_label = null;
    public ?string $checkout_lokasi_pengerjaan_label = null;
    public ?string $checkout_terms_text = null;

    public static function group(): string
    {
        return 'keranjang_checkout';
    }
}
