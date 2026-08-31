<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perbaikan skema: kolom id_paket pada tabel keranjangs tidak dipakai.
     * Tabel keranjangs adalah header (1 baris per user + status active),
     * sedangkan paket layanan disimpan di detail_keranjangs.
     */
    public function up(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            // 1. Cek & Hapus Foreign Key jika ada
            try {
                $table->dropForeign(['id_paket']);
            } catch (\Exception $e) {
                // Abaikan jika Foreign Key tidak ditemukan
            }

            // 2. Cek & Hapus Unique Index jika ada
            try {
                $table->dropUnique(['id_keranjang', 'id_paket']);
            } catch (\Exception $e) {
                // Abaikan jika Unique Index tidak ditemukan
            }

            // 3. Hapus Kolom id_paket jika kolomnya masih ada
            if (Schema::hasColumn('keranjangs', 'id_paket')) {
                $table->dropColumn('id_paket');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            if (!Schema::hasColumn('keranjangs', 'id_paket')) {
                $table->foreignId('id_paket')->nullable()->constrained('layanans', 'id_layanan')->onDelete('cascade');
                $table->unique(['id_keranjang', 'id_paket']);
            }
        });
    }
};