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
            // Beranda CMS
            $table->text('home_title')->nullable();
            $table->text('home_subtitle')->nullable();
            $table->text('home_hero_image')->nullable();
            $table->text('home_feature_title')->nullable();
            $table->text('home_feature_subtitle')->nullable();

            // Auth CMS
            $table->text('login_title')->nullable();
            $table->text('login_subtitle')->nullable();
            $table->text('login_image')->nullable();
            $table->text('login_form_title')->nullable();
            $table->text('login_form_subtitle')->nullable();
            
            $table->text('register_title')->nullable();
            $table->text('register_subtitle')->nullable();
            $table->text('register_image')->nullable();
            $table->text('register_form_title')->nullable();
            $table->text('register_form_subtitle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn([
                'home_title', 'home_subtitle', 'home_hero_image', 'home_feature_title', 'home_feature_subtitle',
                'login_title', 'login_subtitle', 'login_image', 'login_form_title', 'login_form_subtitle',
                'register_title', 'register_subtitle', 'register_image', 'register_form_title', 'register_form_subtitle'
            ]);
        });
    }
};
