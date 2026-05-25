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
            $table->string('layanan_hero_image')->nullable()->after('layanan_hero_desc')->comment('Background image hero Layanan');
            $table->string('galeri_hero_image')->nullable()->after('galeri_hero_desc')->comment('Background image hero Galeri');
        });
    }

    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn(['layanan_hero_image', 'galeri_hero_image']);
        });
    }
};
