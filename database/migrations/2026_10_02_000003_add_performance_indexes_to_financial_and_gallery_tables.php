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
        // 1. Index komposit pada booking_payments untuk query agregasi pendapatan & laporan
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_booking_payments_status_created');
        });

        // 2. Index komposit pada pesanans untuk filtering laporan penjualan & dashboard
        Schema::table('pesanans', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_pesanans_status_created');
        });

        // 3. Index pada galeris untuk filtering katalog kategori & featured showcase
        Schema::table('galeris', function (Blueprint $table) {
            $table->index('kategori', 'idx_galeris_kategori');
            $table->index(['is_featured', 'created_at'], 'idx_galeris_featured_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropIndex('idx_booking_payments_status_created');
        });

        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropIndex('idx_pesanans_status_created');
        });

        Schema::table('galeris', function (Blueprint $table) {
            $table->dropIndex('idx_galeris_kategori');
            $table->dropIndex('idx_galeris_featured_created');
        });
    }
};
