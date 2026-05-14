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
            $table->string('home_prof_title')->nullable();
            $table->string('home_prof_subtitle')->nullable();
            $table->string('home_catalog_title')->nullable();
            $table->string('home_catalog_subtitle')->nullable();
            $table->string('home_recent_title')->nullable();
            $table->string('home_recent_subtitle')->nullable();
            $table->string('home_cta_title')->nullable();
            $table->string('home_cta_subtitle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn([
                'home_prof_title', 'home_prof_subtitle', 
                'home_catalog_title', 'home_catalog_subtitle',
                'home_recent_title', 'home_recent_subtitle',
                'home_cta_title', 'home_cta_subtitle'
            ]);
        });
    }
};
