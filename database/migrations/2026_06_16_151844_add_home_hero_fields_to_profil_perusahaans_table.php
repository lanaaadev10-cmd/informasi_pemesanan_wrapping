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

        $table->string('home_badge_text')->nullable();

        $table->string('home_hero_title_line1')->nullable();

        $table->string('home_hero_title_line2')->nullable();

    });
}

public function down(): void
{
    Schema::table('profil_perusahaans', function (Blueprint $table) {

        $table->dropColumn([
            'home_badge_text',
            'home_hero_title_line1',
            'home_hero_title_line2',
        ]);

    });
}
};
