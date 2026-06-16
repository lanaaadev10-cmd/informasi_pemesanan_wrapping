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

        if (!Schema::hasColumn('profil_perusahaans', 'visi')) {
            $table->text('visi')->nullable();
        }

        if (!Schema::hasColumn('profil_perusahaans', 'misi')) {
            $table->text('misi')->nullable();
        }

        if (!Schema::hasColumn('profil_perusahaans', 'sejarah')) {
            $table->longText('sejarah')->nullable();
        }

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_perusahaans', function (Blueprint $table) {
            //
        });
    }
};
