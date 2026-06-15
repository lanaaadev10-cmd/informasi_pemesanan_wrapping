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
            $table->string('nama_perusahaan');
            $table->text('deskripsi');
            $table->string('alamat');
            $table->string('email');
            $table->string('nomor_telepon');
            $table->string('logo')->nullable();
            $table->text('maps_url')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->longText('sejarah')->nullable();
            $table->json('testimonis_json')->nullable();

            // === SOSIAL MEDIA ===
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('whatsapp_link')->nullable();

            // === META / SEO ===
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // === NAVIGASI ===
            $table->string('nav_beranda')->nullable();
            $table->string('nav_layanan')->nullable();
            $table->string('nav_galeri')->nullable();
            $table->string('nav_tentang_kami')->nullable();
            $table->string('nav_profil_saya')->nullable();
            $table->string('nav_riwayat_pesanan')->nullable();
            $table->string('nav_pembayaran')->nullable();
            $table->string('nav_keranjang')->nullable();
            $table->string('nav_belanja')->nullable();
            $table->string('nav_manajemen')->nullable();
            $table->string('nav_pengaturan')->nullable();
            $table->string('nav_bantuan')->nullable();
            $table->string('nav_keluar')->nullable();
            $table->string('nav_masuk')->nullable();
            $table->string('nav_daftar')->nullable();
            $table->string('nav_pemesanan')->nullable();
            $table->string('nav_dashboard')->nullable();

            // === HEADER / HERO ===
            $table->string('home_subtitle')->nullable();
            $table->string('home_cta_title')->nullable();
            $table->string('home_cta_subtitle')->nullable();
            $table->string('home_badge_text')->nullable();
            $table->string('home_hero_title_line1')->nullable();
            $table->string('home_hero_title_line2')->nullable();

            // === STATISTIK HERO ===
            $table->string('home_stat1_value')->nullable()->default('500+');
            $table->string('home_stat1_label')->nullable()->default('Supercars Wrapped');
            $table->string('home_stat2_value')->nullable()->default('5 Tahun');
            $table->string('home_stat2_label')->nullable()->default('Garansi Material');

            // === KEUNGGULAN CARDS ===
            $table->string('home_keunggulan_card1_title')->nullable();
            $table->text('home_keunggulan_card1_desc')->nullable();
            $table->string('home_keunggulan_card2_title')->nullable();
            $table->text('home_keunggulan_card2_desc')->nullable();
            $table->string('home_keunggulan_card3_title')->nullable();
            $table->text('home_keunggulan_card3_desc')->nullable();
            $table->string('home_keunggulan_card4_title')->nullable();
            $table->text('home_keunggulan_card4_desc')->nullable();

            // === LANGKAH MUDAH (STEPS) ===
            $table->string('home_step1_title')->nullable();
            $table->text('home_step1_desc')->nullable();
            $table->string('home_step2_title')->nullable();
            $table->text('home_step2_desc')->nullable();
            $table->string('home_step3_title')->nullable();
            $table->text('home_step3_desc')->nullable();

            // === ORDERING STEPS (legacy) ===
            $table->string('step_1_title')->nullable();
            $table->text('step_1_desc')->nullable();
            $table->string('step_2_title')->nullable();
            $table->text('step_2_desc')->nullable();
            $table->string('step_3_title')->nullable();
            $table->text('step_3_desc')->nullable();
            $table->string('step_4_title')->nullable();
            $table->text('step_4_desc')->nullable();

            // === DASHBOARD CUSTOMER ===
            $table->string('dashboard_title')->nullable();
            $table->string('dashboard_subtitle')->nullable();
            $table->string('dashboard_member_title')->nullable()->default('Premium Gold');
            $table->string('dashboard_member_desc')->nullable()->default('Satu langkah lagi menuju Platinum');
            $table->integer('dashboard_member_progress')->nullable()->default(85);
            $table->text('dashboard_member_benefits')->nullable();

            // === FAST SERVICE CARDS ===
            $table->string('dashboard_service_1_title')->nullable()->default('Paint Protection Film');
            $table->text('dashboard_service_1_desc')->nullable();
            $table->string('dashboard_service_1_icon')->nullable()->default('ph-shield');
            $table->string('dashboard_service_2_title')->nullable()->default('Color Change');
            $table->text('dashboard_service_2_desc')->nullable();
            $table->string('dashboard_service_2_icon')->nullable()->default('ph-palette');
            $table->string('dashboard_service_3_title')->nullable()->default('Interior Styling');
            $table->text('dashboard_service_3_desc')->nullable();
            $table->string('dashboard_service_3_icon')->nullable()->default('ph-armchair');
            $table->string('dashboard_service_4_title')->nullable()->default('Detailing');
            $table->text('dashboard_service_4_desc')->nullable();
            $table->string('dashboard_service_4_icon')->nullable()->default('ph-sparkles');

            // === DASHBOARD EMPTY STATE ===
            $table->string('dashboard_empty_title')->nullable()->default('Tidak Ada Pengerjaan Aktif');
            $table->text('dashboard_empty_desc')->nullable();

            // === LAYANAN (HALAMAN) ===
            $table->string('layanan_hero_title', 255)->nullable();
            $table->text('layanan_hero_desc')->nullable();
            $table->string('layanan_hero_image')->nullable();
            $table->integer('layanan_grid_columns')->default(4);
            $table->string('layanan_card_style', 50)->default('standard');
            $table->boolean('layanan_show_benefits')->default(true);
            $table->boolean('layanan_show_warranty')->default(true);

            // === LAYANAN 1-4 (legacy CMS fields) ===
            $table->string('layanan_1_nama', 255)->nullable();
            $table->text('layanan_1_deskripsi')->nullable();
            $table->string('layanan_1_harga', 100)->nullable();
            $table->json('layanan_1_fitur')->nullable();
            $table->string('layanan_1_gambar')->nullable();
            $table->string('layanan_2_nama', 255)->nullable();
            $table->text('layanan_2_deskripsi')->nullable();
            $table->string('layanan_2_harga', 100)->nullable();
            $table->json('layanan_2_fitur')->nullable();
            $table->string('layanan_2_gambar')->nullable();
            $table->string('layanan_3_nama', 255)->nullable();
            $table->text('layanan_3_deskripsi')->nullable();
            $table->string('layanan_3_harga', 100)->nullable();
            $table->json('layanan_3_fitur')->nullable();
            $table->string('layanan_3_gambar')->nullable();
            $table->string('layanan_4_nama', 255)->nullable();
            $table->text('layanan_4_deskripsi')->nullable();
            $table->string('layanan_4_harga', 100)->nullable();
            $table->json('layanan_4_fitur')->nullable();
            $table->string('layanan_4_gambar')->nullable();

            // === GARANSI & CTA (Layanan) ===
            $table->string('layanan_garansi_title', 255)->nullable();
            $table->text('layanan_garansi_desc')->nullable();
            $table->string('layanan_cta_title', 255)->nullable();
            $table->text('layanan_cta_desc')->nullable();

            // === BADGE LABELS (Layanan) ===
            $table->string('layanan_badge_1')->nullable();
            $table->string('layanan_badge_2')->nullable();
            $table->string('layanan_badge_3')->nullable();
            $table->string('layanan_badge_4')->nullable();

            // === GALERI ===
            $table->string('galeri_hero_title')->nullable();
            $table->text('galeri_hero_desc')->nullable();
            $table->string('galeri_hero_image')->nullable();
            $table->string('galeri_filter_all_label')->nullable();
            $table->json('galeri_filter_categories')->nullable();

            // === TENTANG KAMI ===
            $table->string('tentang_kami_hero_title', 255)->nullable();
            $table->text('tentang_kami_hero_desc')->nullable();
            $table->string('tentang_kami_hero_image')->nullable();
            $table->string('tentang_kami_team_title', 255)->nullable();
            $table->text('tentang_kami_team_desc')->nullable();
            $table->json('tentang_kami_team_members')->nullable();
            $table->integer('tentang_kami_values_columns')->default(3);
            $table->boolean('tentang_kami_show_values')->default(true);
            $table->boolean('tentang_kami_show_history')->default(true);
            $table->boolean('tentang_kami_show_team')->default(true);

            // === PROFIL (Halaman Profil Perusahaan) ===
            $table->string('profil_hero_title')->nullable();
            $table->text('profil_hero_desc')->nullable();
            $table->text('profil_konten')->nullable();

            // === PESANAN SETTINGS ===
            $table->string('pesanan_page_title_all')->nullable();
            $table->text('pesanan_page_desc_all')->nullable();
            $table->string('pesanan_page_title_active')->nullable();
            $table->text('pesanan_page_desc_active')->nullable();

            // === KERANJANG SETTINGS ===
            $table->string('keranjang_title')->nullable();
            $table->string('keranjang_subtitle')->nullable();
            $table->string('keranjang_hero_text')->nullable();

            // === CHECKOUT SETTINGS ===
            $table->string('cta_konfirmasi_pemesanan')->nullable();
            $table->string('checkout_step_1_label')->nullable();
            $table->string('checkout_step_2_label')->nullable();
            $table->string('checkout_step_3_label')->nullable();
            $table->string('checkout_step_4_label')->nullable();

            // === FOOTER ===
            $table->text('footer_copyright')->nullable();
            $table->string('footer_tentang')->nullable();
            $table->string('footer_layanan')->nullable();
            $table->string('footer_kebijakan_privasi')->nullable();
            $table->string('footer_hubungi_kami')->nullable();
            $table->string('footer_navigasi')->nullable();
            $table->string('footer_instagram')->nullable();
            $table->string('footer_facebook')->nullable();
            $table->string('footer_lokasi')->nullable();

            // === SIDEBAR ===
            $table->string('sidebar_galeri_portofolio')->nullable();
            $table->string('sidebar_pesan_baru')->nullable();

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
