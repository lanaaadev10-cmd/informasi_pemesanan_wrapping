<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            // ─── TENTANG KAMI Section ─────────────────────────────────
            $table->text('tentang_kami_hero_title')->nullable()->after('sejarah')->comment('Judul hero Tentang Kami');
            $table->text('tentang_kami_hero_desc')->nullable()->comment('Deskripsi hero Tentang Kami');
            $table->text('tentang_kami_team_title')->nullable()->comment('Judul section tim');
            $table->text('tentang_kami_team_desc')->nullable()->comment('Deskripsi section tim');

            // Tim members (JSON array untuk fleksibilitas)
            $table->json('tentang_kami_team_members')->nullable()->comment('Data tim dalam format JSON');

            // ─── LAYANAN (Services) Section ──────────────────────────────
            $table->text('layanan_hero_title')->nullable()->comment('Judul hero Layanan');
            $table->text('layanan_hero_desc')->nullable()->comment('Deskripsi hero Layanan');

            // Layanan 1 - Stealth Matte
            $table->text('layanan_1_nama')->nullable()->comment('Nama layanan 1');
            $table->text('layanan_1_deskripsi')->nullable()->comment('Deskripsi layanan 1');
            $table->text('layanan_1_harga')->nullable()->comment('Harga layanan 1');
            $table->json('layanan_1_fitur')->nullable()->comment('Fitur layanan 1 (JSON array)');
            $table->text('layanan_1_gambar')->nullable()->comment('Gambar layanan 1');

            // Layanan 2 - Mirror Glossy
            $table->text('layanan_2_nama')->nullable()->comment('Nama layanan 2');
            $table->text('layanan_2_deskripsi')->nullable()->comment('Deskripsi layanan 2');
            $table->text('layanan_2_harga')->nullable()->comment('Harga layanan 2');
            $table->json('layanan_2_fitur')->nullable()->comment('Fitur layanan 2 (JSON array)');
            $table->text('layanan_2_gambar')->nullable()->comment('Gambar layanan 2');

            // Layanan 3 - Satin Silk
            $table->text('layanan_3_nama')->nullable()->comment('Nama layanan 3');
            $table->text('layanan_3_deskripsi')->nullable()->comment('Deskripsi layanan 3');
            $table->text('layanan_3_harga')->nullable()->comment('Harga layanan 3');
            $table->json('layanan_3_fitur')->nullable()->comment('Fitur layanan 3 (JSON array)');
            $table->text('layanan_3_gambar')->nullable()->comment('Gambar layanan 3');

            // Layanan 4 - Paint Protection
            $table->text('layanan_4_nama')->nullable()->comment('Nama layanan 4');
            $table->text('layanan_4_deskripsi')->nullable()->comment('Deskripsi layanan 4');
            $table->text('layanan_4_harga')->nullable()->comment('Harga layanan 4');
            $table->json('layanan_4_fitur')->nullable()->comment('Fitur layanan 4 (JSON array)');
            $table->text('layanan_4_gambar')->nullable()->comment('Gambar layanan 4');

            // Garansi & CTA Section
            $table->text('layanan_garansi_title')->nullable()->comment('Judul garansi');
            $table->text('layanan_garansi_desc')->nullable()->comment('Deskripsi garansi');
            $table->text('layanan_cta_title')->nullable()->comment('Judul CTA');
            $table->text('layanan_cta_desc')->nullable()->comment('Deskripsi CTA');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            // Tentang Kami
            $table->dropColumn([
                'tentang_kami_hero_title',
                'tentang_kami_hero_desc',
                'tentang_kami_team_title',
                'tentang_kami_team_desc',
                'tentang_kami_team_members',
            ]);

            // Layanan
            $table->dropColumn([
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
            ]);
        });
    }
};