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
            // Dashboard CMS
            $table->text('dashboard_title')->nullable();
            $table->text('dashboard_subtitle')->nullable();

            // Ordering Steps CMS
            $table->text('step_1_title')->nullable();
            $table->text('step_1_desc')->nullable();
            $table->text('step_2_title')->nullable();
            $table->text('step_2_desc')->nullable();
            $table->text('step_3_title')->nullable();
            $table->text('step_3_desc')->nullable();
            $table->text('step_4_title')->nullable();
            $table->text('step_4_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn([
                'dashboard_title', 'dashboard_subtitle',
                'step_1_title', 'step_1_desc',
                'step_2_title', 'step_2_desc',
                'step_3_title', 'step_3_desc',
                'step_4_title', 'step_4_desc',
            ]);
        });
    }
};
