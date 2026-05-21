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
            // ─── TENTANG KAMI Layout Config ─────────────────────────────
            $table->integer('tentang_kami_values_columns')->default(3)->comment('Jumlah kolom untuk section nilai (3 atau 4)');
            $table->boolean('tentang_kami_show_values')->default(true)->comment('Tampilkan section nilai');
            $table->boolean('tentang_kami_show_history')->default(true)->comment('Tampilkan section sejarah');
            $table->boolean('tentang_kami_show_team')->default(true)->comment('Tampilkan section tim');

            // ─── LAYANAN Layout Config ──────────────────────────────────
            $table->integer('layanan_grid_columns')->default(4)->comment('Jumlah kolom grid untuk service cards (3 atau 4)');
            $table->string('layanan_card_style', 50)->default('standard')->comment('Style service cards: standard, compact, large');
            $table->boolean('layanan_show_benefits')->default(true)->comment('Tampilkan section Mengapa Memilih Kami');
            $table->boolean('layanan_show_warranty')->default(true)->comment('Tampilkan section garansi');

            // ─── GLOBAL Layout & Style Config ───────────────────────────
            $table->string('accent_color', 50)->default('#f2994a')->comment('Primary accent color (hex)');
            $table->string('primary_layout', 50)->default('full')->comment('Layout style: full, compact');
            $table->boolean('dark_mode')->default(true)->comment('Enable dark mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            // Tentang Kami Config
            $table->dropColumn([
                'tentang_kami_values_columns',
                'tentang_kami_show_values',
                'tentang_kami_show_history',
                'tentang_kami_show_team',
            ]);

            // Layanan Config
            $table->dropColumn([
                'layanan_grid_columns',
                'layanan_card_style',
                'layanan_show_benefits',
                'layanan_show_warranty',
            ]);

            // Global Config
            $table->dropColumn([
                'accent_color',
                'primary_layout',
                'dark_mode',
            ]);
        });
    }
};
