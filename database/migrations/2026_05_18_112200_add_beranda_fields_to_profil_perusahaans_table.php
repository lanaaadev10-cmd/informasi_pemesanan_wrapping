<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration ini hanya menambahkan kolom yang belum ada di tabel profil_perusahaans.
 * Kolom berikut sudah ada sebelumnya dan TIDAK ditambahkan ulang:
 * home_subtitle, home_cta_title, home_cta_subtitle,
 * home_badge_text, home_hero_title_line1, home_hero_title_line2
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            // Statistik Hero
            if (!Schema::hasColumn('profil_perusahaans', 'home_stat1_value'))
                $table->string('home_stat1_value', 100)->nullable()->default('500+');
            if (!Schema::hasColumn('profil_perusahaans', 'home_stat1_label'))
                $table->string('home_stat1_label', 100)->nullable()->default('Supercars Wrapped');
            if (!Schema::hasColumn('profil_perusahaans', 'home_stat2_value'))
                $table->string('home_stat2_value', 100)->nullable()->default('5 Tahun');
            if (!Schema::hasColumn('profil_perusahaans', 'home_stat2_label'))
                $table->string('home_stat2_label', 100)->nullable()->default('Garansi Material');

            // Keunggulan Cards
            if (!Schema::hasColumn('profil_perusahaans', 'home_keunggulan_card1_title'))
                $table->text('home_keunggulan_card1_title')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_keunggulan_card1_desc'))
                $table->text('home_keunggulan_card1_desc')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_keunggulan_card2_title'))
                $table->text('home_keunggulan_card2_title')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_keunggulan_card2_desc'))
                $table->text('home_keunggulan_card2_desc')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_keunggulan_card3_title'))
                $table->text('home_keunggulan_card3_title')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_keunggulan_card3_desc'))
                $table->text('home_keunggulan_card3_desc')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_keunggulan_card4_title'))
                $table->text('home_keunggulan_card4_title')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_keunggulan_card4_desc'))
                $table->text('home_keunggulan_card4_desc')->nullable();

            // Langkah Mudah Steps (step_1 s/d step_4 sudah ada, kita pakai nama baru home_step*)
            if (!Schema::hasColumn('profil_perusahaans', 'home_step1_title'))
                $table->text('home_step1_title')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_step1_desc'))
                $table->text('home_step1_desc')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_step2_title'))
                $table->text('home_step2_title')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_step2_desc'))
                $table->text('home_step2_desc')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_step3_title'))
                $table->text('home_step3_title')->nullable();
            if (!Schema::hasColumn('profil_perusahaans', 'home_step3_desc'))
                $table->text('home_step3_desc')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $cols = [
                'home_stat1_value', 'home_stat1_label', 'home_stat2_value', 'home_stat2_label',
                'home_keunggulan_card1_title', 'home_keunggulan_card1_desc',
                'home_keunggulan_card2_title', 'home_keunggulan_card2_desc',
                'home_keunggulan_card3_title', 'home_keunggulan_card3_desc',
                'home_keunggulan_card4_title', 'home_keunggulan_card4_desc',
                'home_step1_title', 'home_step1_desc',
                'home_step2_title', 'home_step2_desc',
                'home_step3_title', 'home_step3_desc',
            ];
            // Hanya drop kolom yang benar-benar ada
            $existing = array_filter($cols, fn($c) => Schema::hasColumn('profil_perusahaans', $c));
            if ($existing) $table->dropColumn(array_values($existing));
        });
    }
};
