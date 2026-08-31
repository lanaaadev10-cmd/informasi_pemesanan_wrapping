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
        // Hanya jalankan pembersihan jika kolom id_paket memang ada di tabel
        if (Schema::hasColumn('keranjangs', 'id_paket')) {
            Schema::table('keranjangs', function (Blueprint $table) {
                // Hapus foreign & unique index dengan try-catch di tingkat internal
                try {
                    $table->dropForeign('keranjangs_id_paket_foreign');
                } catch (\Throwable $e) {}

                try {
                    $table->dropUnique('keranjangs_id_keranjang_id_paket_unique');
                } catch (\Throwable $e) {}

                $table->dropColumn('id_paket');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('keranjangs', 'id_paket')) {
            Schema::table('keranjangs', function (Blueprint $table) {
                $table->foreignId('id_paket')->nullable()->constrained('layanans', 'id_layanan')->onDelete('cascade');
            });
        }
    }
};