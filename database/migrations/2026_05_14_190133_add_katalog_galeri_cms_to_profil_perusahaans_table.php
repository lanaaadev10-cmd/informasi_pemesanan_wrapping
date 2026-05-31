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
            $table->text('katalog_title')->nullable();
            $table->text('katalog_subtitle')->nullable();
            $table->text('galeri_title')->nullable();
            $table->text('galeri_subtitle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn(['katalog_title', 'katalog_subtitle', 'galeri_title', 'galeri_subtitle']);
        });
    }
};
