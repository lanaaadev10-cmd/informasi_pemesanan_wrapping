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
            $table->index('id_user');
            $table->index('status');
            $table->index('tanggal_pesan');
            $table->index(['id_user', 'status']);
        });

        Schema::table('keranjangs', function (Blueprint $table) {
            $table->index(['id_user', 'status']);
        });

        Schema::table('detail_keranjangs', function (Blueprint $table) {
            $table->unique(['id_keranjang', 'id_paket']); // Prevent duplicate items
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->index('status');
            $table->index(['status', 'updated_at']);
        });

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

        Schema::table('detail_keranjangs', function (Blueprint $table) {
            $table->dropUnique(['id_keranjang', 'id_paket']);
        });

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
