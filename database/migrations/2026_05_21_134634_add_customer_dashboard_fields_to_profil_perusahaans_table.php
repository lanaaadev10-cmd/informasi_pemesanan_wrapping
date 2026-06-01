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
            // Dashboard CMS - Member Status Card (Diubah ke text agar ringan)
            $table->text('dashboard_member_title')->nullable();
            $table->text('dashboard_member_desc')->nullable();
            $table->integer('dashboard_member_progress')->nullable()->default(85);
            $table->text('dashboard_member_benefits')->nullable();

            // Dashboard CMS - Fast Services (4 Cards)
            $table->text('dashboard_service_1_title')->nullable();
            $table->text('dashboard_service_1_desc')->nullable();
            $table->text('dashboard_service_1_icon')->nullable();

            $table->text('dashboard_service_2_title')->nullable();
            $table->text('dashboard_service_2_desc')->nullable();
            $table->text('dashboard_service_2_icon')->nullable();

            $table->text('dashboard_service_3_title')->nullable();
            $table->text('dashboard_service_3_desc')->nullable();
            $table->text('dashboard_service_3_icon')->nullable();

            $table->text('dashboard_service_4_title')->nullable();
            $table->text('dashboard_service_4_desc')->nullable();
            $table->text('dashboard_service_4_icon')->nullable();

            // Dashboard CMS - Empty State Card
            $table->text('dashboard_empty_title')->nullable();
            $table->text('dashboard_empty_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            $table->dropColumn([
                'dashboard_member_title',
                'dashboard_member_desc',
                'dashboard_member_progress',
                'dashboard_member_benefits',
                'dashboard_service_1_title',
                'dashboard_service_1_desc',
                'dashboard_service_1_icon',
                'dashboard_service_2_title',
                'dashboard_service_2_desc',
                'dashboard_service_2_icon',
                'dashboard_service_3_title',
                'dashboard_service_3_desc',
                'dashboard_service_3_icon',
                'dashboard_service_4_title',
                'dashboard_service_4_desc',
                'dashboard_service_4_icon',
                'dashboard_empty_title',
                'dashboard_empty_desc',
            ]);
        });
    }
};