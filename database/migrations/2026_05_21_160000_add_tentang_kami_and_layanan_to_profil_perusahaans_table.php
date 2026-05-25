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
            $table->string('tentang_kami_hero_title', 255)->nullable()->after('sejarah')->comment('Judul hero Tentang Kami');
            $table->text('tentang_kami_hero_desc')->nullable()->comment('Deskripsi hero Tentang Kami');
            $table->string('tentang_kami_team_title', 255)->nullable()->comment('Judul section tim');
            $table->text('tentang_kami_team_desc')->nullable()->comment('Deskripsi section tim');

            // Tim members (JSON array untuk fleksibilitas)
            $table->json('tentang_kami_team_members')->nullable()->comment('Data tim dalam format JSON');

            // ─── LAYANAN (Services) Section ──────────────────────────────
            $table->string('layanan_hero_title', 255)->nullable()->comment('Judul hero Layanan');
            $table->text('layanan_hero_desc')->nullable()->comment('Deskripsi hero Layanan');

            // Layanan 1 - Stealth Matte
            $table->string('layanan_1_nama', 255)->nullable()->comment('Nama layanan 1');
            $table->text('layanan_1_deskripsi')->nullable()->comment('Deskripsi layanan 1');
            $table->string('layanan_1_harga', 100)->nullable()->comment('Harga layanan 1');
            $table->json('layanan_1_fitur')->nullable()->comment('Fitur layanan 1 (JSON array)');
            $table->string('layanan_1_gambar')->nullable()->comment('Gambar layanan 1');

            // Layanan 2 - Mirror Glossy
            $table->string('layanan_2_nama', 255)->nullable()->comment('Nama layanan 2');
            $table->text('layanan_2_deskripsi')->nullable()->comment('Deskripsi layanan 2');
            $table->string('layanan_2_harga', 100)->nullable()->comment('Harga layanan 2');
            $table->json('layanan_2_fitur')->nullable()->comment('Fitur layanan 2 (JSON array)');
            $table->string('layanan_2_gambar')->nullable()->comment('Gambar layanan 2');

            // Layanan 3 - Satin Silk
            $table->string('layanan_3_nama', 255)->nullable()->comment('Nama layanan 3');
            $table->text('layanan_3_deskripsi')->nullable()->comment('Deskripsi layanan 3');
            $table->string('layanan_3_harga', 100)->nullable()->comment('Harga layanan 3');
            $table->json('layanan_3_fitur')->nullable()->comment('Fitur layanan 3 (JSON array)');
            $table->string('layanan_3_gambar')->nullable()->comment('Gambar layanan 3');

            // Layanan 4 - Paint Protection
            $table->string('layanan_4_nama', 255)->nullable()->comment('Nama layanan 4');
            $table->text('layanan_4_deskripsi')->nullable()->comment('Deskripsi layanan 4');
            $table->string('layanan_4_harga', 100)->nullable()->comment('Harga layanan 4');
            $table->json('layanan_4_fitur')->nullable()->comment('Fitur layanan 4 (JSON array)');
            $table->string('layanan_4_gambar')->nullable()->comment('Gambar layanan 4');

            // Garansi & CTA Section
            $table->string('layanan_garansi_title', 255)->nullable()->comment('Judul garansi');
            $table->text('layanan_garansi_desc')->nullable()->comment('Deskripsi garansi');
            $table->string('layanan_cta_title', 255)->nullable()->comment('Judul CTA');
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
