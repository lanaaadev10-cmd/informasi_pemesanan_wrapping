<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan composite indexes untuk mempercepat query filtering dashboard & laporan pesanan.
     */
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->index(['id_user', 'status'], 'idx_pesanans_user_status');
            $table->index(['tanggal_pesan', 'status'], 'idx_pesanans_tanggal_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropIndex('idx_pesanans_user_status');
            $table->dropIndex('idx_pesanans_tanggal_status');
        });
    }
};
