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
        // 1. Indeks untuk Tabel Pesanans
        Schema::table('pesanans', function (Blueprint $table) {
            $table->index('id_user');
            $table->index('status');
            $table->index('tanggal_pesan');
            $table->index(['id_user', 'status']);
        });

        // 2. Indeks untuk Tabel Keranjangs
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->index(['id_user', 'status']);
        });

        // 3. PERBAIKAN: Aturan unik dipindah ke detail_keranjangs tempat kolom id_paket berada
        Schema::table('detail_keranjangs', function (Blueprint $table) {
            $table->unique(['id_keranjang', 'id_paket'], 'detail_keranjangs_id_keranjang_id_paket_unique');
        });

        // 4. PERBAIKAN: Mengubah 'status_pembayaran' menjadi 'status' sesuai kolom asli di database
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->index('status');
            $table->index(['status', 'updated_at']);
        });

        // 5. Indeks untuk Tabel Notifikasis
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->index(['id_user', 'is_read']);
            $table->index(['id_user', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropIndex(['id_user']);
            $table->dropIndex(['status']);
            $table->dropIndex(['tanggal_pesan']);
            $table->dropIndex(['id_user', 'status']);
        });

        Schema::table('keranjangs', function (Blueprint $table) {
            $table->dropIndex(['id_user', 'status']);
        });

        // Rollback perbaikan di detail_keranjangs
        Schema::table('detail_keranjangs', function (Blueprint $table) {
            $table->dropUnique('detail_keranjangs_id_keranjang_id_paket_unique');
        });

        // Rollback perbaikan di pembayarans
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['status', 'updated_at']);
        });

        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropIndex(['id_user', 'is_read']);
            $table->dropIndex(['id_user', 'created_at']);
        });
    }
};