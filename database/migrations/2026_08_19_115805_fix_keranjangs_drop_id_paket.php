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
            $table->dropForeign(['id_paket']);
            $table->dropUnique(['id_keranjang', 'id_paket']);
            $table->dropColumn('id_paket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->foreignId('id_paket')->constrained('layanans', 'id_layanan')->onDelete('cascade');
            $table->unique(['id_keranjang', 'id_paket']);
        });
    }
};