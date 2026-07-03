<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PesananSettings extends Settings
{
    public ?string $pesanan_page_title_all = null;
    public ?string $pesanan_page_desc_all = null;
    public ?string $pesanan_filter_semua_label = null;
    public ?string $pesanan_filter_berjalan_label = null;
    public ?string $pesanan_filter_selesai_label = null;
    public ?string $pesanan_empty_state_title = null;
    public ?string $pesanan_empty_state_desc = null;
    public ?string $pesanan_new_order_button_label = null;

    public ?string $pesanan_nama_bank = null;
    public ?string $pesanan_nomor_rekening = null;
    public ?string $pesanan_atas_nama = null;

    public static function group(): string
    {
        return 'pesanan';
    }
}
