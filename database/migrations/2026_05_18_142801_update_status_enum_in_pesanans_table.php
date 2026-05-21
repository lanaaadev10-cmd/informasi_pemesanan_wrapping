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
        Schema::table('pesanans', function (Blueprint $table) {
            $table->string('status')->default('menunggu_konfirmasi_admin')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu_verifikasi',
                'perlu_diperbaiki',
                'diverifikasi',
                'menunggu_pembayaran',
                'dibayar',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi')->change();
        });
    }
};
