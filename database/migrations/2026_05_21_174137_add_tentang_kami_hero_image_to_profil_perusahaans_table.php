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
            $table->string('tentang_kami_hero_image')->nullable()->after('tentang_kami_hero_desc')->comment('Background image hero Tentang Kami');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn('tentang_kami_hero_image');
        });
    }
};
