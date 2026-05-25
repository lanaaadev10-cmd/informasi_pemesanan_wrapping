<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use App\Models\Traits\HasCompanyCms;

/**
 * Model ProfilPerusahaan
 *
 * Model singleton — hanya ada 1 record profil perusahaan di database.
 * Dikelola melalui Admin Panel Filament v5.
 */
class ProfilPerusahaan extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        // Informasi Utama
        'nama_perusahaan',
        'deskripsi',
        'alamat',
        'email',
        'nomor_telepon',
        'logo',
        'maps_url',

        // CMS Dinamis — Tentang Kami
        'visi',
        'misi',
        'sejarah',

        // Sosial Media
        'instagram_url',
        'facebook_url',
        'tiktok_url',
        'whatsapp_url',

        // SEO & Metadata
        'meta_title',
        'meta_description',

        // ─── CMS Beranda (Homepage) ────────────────────────────
        // Hero Section
        'home_badge_text',
        'home_hero_title_line1',
        'home_hero_title_line2',
        'home_subtitle',
        'home_stat1_value',
        'home_stat1_label',
        'home_stat2_value',
        'home_stat2_label',
        // Keunggulan Section
        'home_keunggulan_card1_title',
        'home_keunggulan_card1_desc',
        'home_keunggulan_card2_title',
        'home_keunggulan_card2_desc',
        'home_keunggulan_card3_title',
        'home_keunggulan_card3_desc',
        'home_keunggulan_card4_title',
        'home_keunggulan_card4_desc',
        // CTA Section
        'home_cta_title',
        'home_cta_subtitle',
        // Langkah Mudah Section
        'home_step1_title',
        'home_step1_desc',
        'home_step2_title',
        'home_step2_desc',
        'home_step3_title',
        'home_step3_desc',

        // ─── CMS Dashboard Customer ────────────────────────────
        'dashboard_member_title',
        'dashboard_member_desc',
        'dashboard_member_progress',
        'dashboard_member_benefits',
        'dashboard_service_1_title',
        'dashboard_service_1_desc',
        'dashboard_service_1_icon',
        'dashboard_service_2_title',
        'dashboard_service_2_desc',
        'dashboard_service_2_icon',
        'dashboard_service_3_title',
        'dashboard_service_3_desc',
        'dashboard_service_3_icon',
        'dashboard_service_4_title',
        'dashboard_service_4_desc',
        'dashboard_service_4_icon',
        'dashboard_empty_title',
        'dashboard_empty_desc',

        // ─── CMS Tentang Kami ──────────────────────────────────
        'tentang_kami_hero_title',
        'tentang_kami_hero_desc',
        'tentang_kami_hero_image',
        'tentang_kami_team_title',
        'tentang_kami_team_desc',
        'tentang_kami_team_members',

        // ─── CMS Layanan (Services) ────────────────────────────
        'layanan_hero_title',
        'layanan_hero_desc',
        'layanan_1_nama',
        'layanan_1_deskripsi',
        'layanan_1_harga',
        'layanan_1_fitur',
        'layanan_1_gambar',
        'layanan_2_nama',
        'layanan_2_deskripsi',
        'layanan_2_harga',
        'layanan_2_fitur',
        'layanan_2_gambar',
        'layanan_3_nama',
        'layanan_3_deskripsi',
        'layanan_3_harga',
        'layanan_3_fitur',
        'layanan_3_gambar',
        'layanan_4_nama',
        'layanan_4_deskripsi',
        'layanan_4_harga',
        'layanan_4_fitur',
        'layanan_4_gambar',
        'layanan_garansi_title',
        'layanan_garansi_desc',
        'layanan_cta_title',
        'layanan_cta_desc',

        // ─── Layout & Style Configuration ────────────────────
        // Tentang Kami Config
        'tentang_kami_values_columns',
        'tentang_kami_show_values',
        'tentang_kami_show_history',
        'tentang_kami_show_team',

        // Layanan Config
        'layanan_grid_columns',
        'layanan_card_style',
        'layanan_show_benefits',
        'layanan_show_warranty',

        // Global Config
        'accent_color',
        'primary_layout',
        'dark_mode',

        // ─── Katalog Page CMS ────────────────────────────────
        'katalog_hero_title',
        'katalog_hero_desc',
        'katalog_intro_text',
        'katalog_empty_state_title',
        'katalog_empty_state_desc',
        'katalog_feature_1_title',
        'katalog_feature_1_desc',
        'katalog_feature_2_title',
        'katalog_feature_2_desc',
        'katalog_feature_3_title',
        'katalog_feature_3_desc',
        'katalog_feature_4_title',
        'katalog_feature_4_desc',

        // ─── Galeri Page CMS ─────────────────────────────────
        'galeri_hero_title',
        'galeri_hero_desc',
        'galeri_intro_text',
        'galeri_empty_state_title',
        'galeri_empty_state_desc',

        // ─── Pesanan Page CMS ────────────────────────────────
        'pesanan_page_title_all',
        'pesanan_page_desc_all',
        'pesanan_filter_semua_label',
        'pesanan_filter_berjalan_label',
        'pesanan_filter_selesai_label',
        'pesanan_empty_state_title',
        'pesanan_empty_state_desc',
        'pesanan_new_order_button_label',

        // ─── Keranjang Page CMS ──────────────────────────────
        'keranjang_hero_text',
        'keranjang_title',
        'keranjang_subtitle',
        'keranjang_empty_title',
        'keranjang_empty_desc',
        'keranjang_warranty_title',
        'keranjang_warranty_desc',
        'keranjang_service_charge_label',
        'keranjang_service_charge_amount',
        'keranjang_checkout_button_label',

        // ─── Checkout Page CMS ───────────────────────────────
        'checkout_step_1_label',
        'checkout_step_2_label',
        'checkout_step_3_label',
        'checkout_step_4_label',
        'checkout_step2_title',
        'checkout_step2_subtitle',
        'checkout_step3_title',
        'checkout_step3_subtitle',
        'checkout_warranty_badge_1',
        'checkout_warranty_badge_2',
        'checkout_confirm_button_label',

        // ─── Profil Page CMS ─────────────────────────────────
        'profil_pillar_1_title',
        'profil_pillar_1_desc',
        'profil_pillar_2_title',
        'profil_pillar_2_desc',
        'profil_pillar_3_title',
        'profil_pillar_3_desc',

        // ─── Dashboard Section Headers ───────────────────────
        'dashboard_quick_services_title',
        'dashboard_activity_title',
    ];

    /**
     * Type casting untuk kolom tertentu.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'tentang_kami_team_members' => 'json',
            'layanan_1_fitur' => 'json',
            'layanan_2_fitur' => 'json',
            'layanan_3_fitur' => 'json',
            'layanan_4_fitur' => 'json',
            // Layout Config Casts
            'tentang_kami_values_columns' => 'integer',
            'tentang_kami_show_values' => 'boolean',
            'tentang_kami_show_history' => 'boolean',
            'tentang_kami_show_team' => 'boolean',
            'layanan_grid_columns' => 'integer',
            'layanan_show_benefits' => 'boolean',
            'layanan_show_warranty' => 'boolean',
            'dark_mode' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('site_profile');
        });
        static::deleted(function () {
            Cache::forget('site_profile');
        });
    }

    /**
     * Helper: Ambil profil perusahaan (singleton pattern).
     * Selalu mengembalikan record pertama, atau null jika belum ada.
     */
    public static function singleton(): ?self
    {
        return static::first();
    }

    /**
     * Accessor: URL lengkap logo untuk ditampilkan di frontend.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return asset('storage/' . $this->logo);
    }

    /**
     * Accessor: Link WhatsApp langsung dari nomor telepon.
     */
    public function getWhatsappLinkAttribute(): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $this->nomor_telepon ?? '');

        // Ganti awalan 0 dengan 62 (kode negara Indonesia)
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        return "https://wa.me/{$nomor}";
    }
}
