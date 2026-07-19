<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_perusahaans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // === INFORMASI DASAR PERUSAHAAN ===
            $table->string('nama_perusahaan', 150);
            $table->text('deskripsi');
            $table->text('alamat');
            $table->string('email', 100);
            $table->string('nomor_telepon', 50);
            $table->text('logo')->nullable();
            $table->text('maps_url')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->longText('sejarah')->nullable();
            $table->json('testimonis_json')->nullable();

            // === SOSIAL MEDIA ===
            $table->text('instagram_url')->nullable();
            $table->text('facebook_url')->nullable();
            $table->text('tiktok_url')->nullable();
            $table->text('whatsapp_link')->nullable();

            // === META / SEO ===
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // === NAVIGASI ===
            $table->text('nav_beranda')->nullable();
            $table->text('nav_layanan')->nullable();
            $table->text('nav_galeri')->nullable();
            $table->text('nav_tentang_kami')->nullable();
            $table->text('nav_profil_saya')->nullable();
            $table->text('nav_riwayat_pesanan')->nullable();
            $table->text('nav_pembayaran')->nullable();
            $table->text('nav_keranjang')->nullable();
            $table->text('nav_belanja')->nullable();
            $table->text('nav_manajemen')->nullable();
            $table->text('nav_pengaturan')->nullable();
            $table->text('nav_bantuan')->nullable();
            $table->text('nav_keluar')->nullable();
            $table->text('nav_masuk')->nullable();
            $table->text('nav_daftar')->nullable();
            $table->text('nav_pemesanan')->nullable();
            $table->text('nav_dashboard')->nullable();

            // === HEADER / HERO ===
            $table->text('home_subtitle')->nullable();
            $table->text('home_cta_title')->nullable();
            $table->text('home_cta_subtitle')->nullable();
            $table->text('home_badge_text')->nullable();
            $table->text('home_hero_title_line1')->nullable();
            $table->text('home_hero_title_line2')->nullable();

            // === STATISTIK HERO ===
            $table->text('home_stat1_value')->nullable();
            $table->text('home_stat1_label')->nullable();
            $table->text('home_stat2_value')->nullable();
            $table->text('home_stat2_label')->nullable();

            // === KEUNGGULAN CARDS ===
            $table->text('home_keunggulan_card1_title')->nullable();
            $table->text('home_keunggulan_card1_desc')->nullable();
            $table->text('home_keunggulan_card2_title')->nullable();
            $table->text('home_keunggulan_card2_desc')->nullable();
            $table->text('home_keunggulan_card3_title')->nullable();
            $table->text('home_keunggulan_card3_desc')->nullable();
            $table->text('home_keunggulan_card4_title')->nullable();
            $table->text('home_keunggulan_card4_desc')->nullable();

            // === LANGKAH MUDAH (STEPS) ===
            $table->text('home_step1_title')->nullable();
            $table->text('home_step1_desc')->nullable();
            $table->text('home_step2_title')->nullable();
            $table->text('home_step2_desc')->nullable();
            $table->text('home_step3_title')->nullable();
            $table->text('home_step3_desc')->nullable();

            // === ORDERING STEPS (legacy) ===
            $table->text('step_1_title')->nullable();
            $table->text('step_1_desc')->nullable();
            $table->text('step_2_title')->nullable();
            $table->text('step_2_desc')->nullable();
            $table->text('step_3_title')->nullable();
            $table->text('step_3_desc')->nullable();
            $table->text('step_4_title')->nullable();
            $table->text('step_4_desc')->nullable();

            // === DASHBOARD CUSTOMER ===
            $table->text('dashboard_title')->nullable();
            $table->text('dashboard_subtitle')->nullable();
            $table->text('dashboard_member_title')->nullable();
            $table->text('dashboard_member_desc')->nullable();
            $table->integer('dashboard_member_progress')->nullable()->default(85);
            $table->text('dashboard_member_benefits')->nullable();

            // === FAST SERVICE CARDS ===
            $table->text('dashboard_service_1_title')->nullable();
            $table->text('dashboard_service_1_desc')->nullable();
            $table->text('dashboard_service_1_icon')->nullable();
            $table->text('dashboard_service_2_title')->nullable();
            $table->text('dashboard_service_2_desc')->nullable();
            $table->text('dashboard_service_2_icon')->nullable();
            $table->text('dashboard_service_3_title')->nullable();
            $table->text('dashboard_service_3_desc')->nullable();
            $table->text('dashboard_service_3_icon')->nullable();
            $table->text('dashboard_service_4_title')->nullable();
            $table->text('dashboard_service_4_desc')->nullable();
            $table->text('dashboard_service_4_icon')->nullable();

            // === DASHBOARD EMPTY STATE ===
            $table->text('dashboard_empty_title')->nullable();
            $table->text('dashboard_empty_desc')->nullable();

            // === LAYANAN (HALAMAN) ===
            $table->text('layanan_hero_title')->nullable();
            $table->text('layanan_hero_desc')->nullable();
            $table->text('layanan_hero_image')->nullable();
            $table->integer('layanan_grid_columns')->default(4);
            $table->string('layanan_card_style', 50)->default('standard');
            $table->boolean('layanan_show_benefits')->default(true);
            $table->boolean('layanan_show_warranty')->default(true);

            // === LAYANAN 1-4 (legacy CMS fields) ===
            $table->text('layanan_1_nama')->nullable();
            $table->text('layanan_1_deskripsi')->nullable();
            $table->text('layanan_1_harga')->nullable();
            $table->json('layanan_1_fitur')->nullable();
            $table->text('layanan_1_gambar')->nullable();
            $table->text('layanan_2_nama')->nullable();
            $table->text('layanan_2_deskripsi')->nullable();
            $table->text('layanan_2_harga')->nullable();
            $table->json('layanan_2_fitur')->nullable();
            $table->text('layanan_2_gambar')->nullable();
            $table->text('layanan_3_nama')->nullable();
            $table->text('layanan_3_deskripsi')->nullable();
            $table->text('layanan_3_harga')->nullable();
            $table->json('layanan_3_fitur')->nullable();
            $table->text('layanan_3_gambar')->nullable();
            $table->text('layanan_4_nama')->nullable();
            $table->text('layanan_4_deskripsi')->nullable();
            $table->text('layanan_4_harga')->nullable();
            $table->json('layanan_4_fitur')->nullable();
            $table->text('layanan_4_gambar')->nullable();

            // === GARANSI & CTA (Layanan) ===
            $table->text('layanan_garansi_title')->nullable();
            $table->text('layanan_garansi_desc')->nullable();
            $table->text('layanan_cta_title')->nullable();
            $table->text('layanan_cta_desc')->nullable();

            // === BADGE LABELS (Layanan) ===
            $table->text('layanan_badge_1')->nullable();
            $table->text('layanan_badge_2')->nullable();
            $table->text('layanan_badge_3')->nullable();
            $table->text('layanan_badge_4')->nullable();

            // === GALERI ===
            $table->text('galeri_hero_title')->nullable();
            $table->text('galeri_hero_desc')->nullable();
            $table->text('galeri_hero_image')->nullable();
            $table->text('galeri_filter_all_label')->nullable();
            $table->json('galeri_filter_categories')->nullable();

            // === TENTANG KAMI ===
            $table->text('tentang_kami_hero_title')->nullable();
            $table->text('tentang_kami_hero_desc')->nullable();
            $table->text('tentang_kami_hero_image')->nullable();
            $table->text('tentang_kami_team_title')->nullable();
            $table->text('tentang_kami_team_desc')->nullable();
            $table->json('tentang_kami_team_members')->nullable();
            $table->integer('tentang_kami_values_columns')->default(3);
            $table->boolean('tentang_kami_show_values')->default(true);
            $table->boolean('tentang_kami_show_history')->default(true);
            $table->boolean('tentang_kami_show_team')->default(true);

            // === PROFIL (Halaman Profil Perusahaan) ===
            $table->text('profil_hero_title')->nullable();
            $table->text('profil_hero_desc')->nullable();
            $table->text('profil_konten')->nullable();

            // === PESANAN SETTINGS ===
            $table->text('pesanan_page_title_all')->nullable();
            $table->text('pesanan_page_desc_all')->nullable();
            $table->text('pesanan_page_title_active')->nullable();
            $table->text('pesanan_page_desc_active')->nullable();

            // === KERANJANG SETTINGS ===
            $table->text('keranjang_title')->nullable();
            $table->text('keranjang_subtitle')->nullable();
            $table->text('keranjang_hero_text')->nullable();

            // === CHECKOUT SETTINGS ===
            $table->text('cta_konfirmasi_pemesanan')->nullable();
            $table->text('checkout_step_1_label')->nullable();
            $table->text('checkout_step_2_label')->nullable();
            $table->text('checkout_step_3_label')->nullable();
            $table->text('checkout_step_4_label')->nullable();

            // === FOOTER ===
            $table->text('footer_copyright')->nullable();
            $table->text('footer_tentang')->nullable();
            $table->text('footer_layanan')->nullable();
            $table->text('footer_kebijakan_privasi')->nullable();
            $table->text('footer_hubungi_kami')->nullable();
            $table->text('footer_navigasi')->nullable();
            $table->text('footer_instagram')->nullable();
            $table->text('footer_facebook')->nullable();
            $table->text('footer_lokasi')->nullable();

            // === SIDEBAR ===
            $table->text('sidebar_galeri_portofolio')->nullable();
            $table->text('sidebar_pesan_baru')->nullable();

            // === GLOBAL LAYOUT & STYLE ===
            $table->string('accent_color', 50)->default('#f2994a');
            $table->string('primary_layout', 50)->default('full');
            $table->boolean('dark_mode')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_perusahaans');
    }
};
