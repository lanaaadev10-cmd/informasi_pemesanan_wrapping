<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perbaikan skema: kolom id_paket pada tabel keranjangs tidak dipakai.
     */
    public function up(): void
    {
        // Kolom id_paket sudah tidak ada di migration pembuat tabel keranjangs (2026_05_12_100000).
        // Guard ini membuat migration aman dijalankan pada database segar (migrate:fresh / test).
        if (Schema::hasColumn('keranjangs', 'id_paket')) {
            Schema::table('keranjangs', function (Blueprint $table) {
                $table->dropForeign(['id_paket']);
                $table->dropUnique(['id_keranjang', 'id_paket']);
                $table->dropColumn('id_paket');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('keranjangs', 'id_paket')) {
            Schema::table('keranjangs', function (Blueprint $table) {
                $table->foreignId('id_paket')->constrained('layanans', 'id_layanan')->onDelete('cascade');
                $table->unique(['id_keranjang', 'id_paket']);
            });
        }
    }
};
