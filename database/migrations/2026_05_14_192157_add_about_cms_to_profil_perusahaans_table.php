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
            $table->string('about_hero_title')->nullable();
            $table->string('about_hero_subtitle')->nullable();
            $table->string('stats_experience')->nullable();
            $table->string('stats_projects')->nullable();
            $table->string('stats_satisfaction')->nullable();
            $table->string('stats_support')->nullable();
            $table->string('about_feature_title')->nullable();
            $table->text('about_feature_desc')->nullable();
            $table->string('about_feature_image')->nullable();
            $table->json('about_feature_list')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn([
                'about_hero_title', 'about_hero_subtitle', 'stats_experience', 
                'stats_projects', 'stats_satisfaction', 'stats_support',
                'about_feature_title', 'about_feature_desc', 'about_feature_image',
                'about_feature_list'
            ]);
        });
    }
};
