<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan composite indexes untuk optimasi performa query dashboard,
     * filter booking customer, perhitungan badge keranjang, dan ulasan publik.
     */
    public function up(): void
    {
        // 1. Index komposit pada bookings untuk query filter status & histori user
        Schema::table('bookings', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'idx_bookings_user_status');
            $table->index(['user_id', 'booking_date'], 'idx_bookings_user_booking_date');
        });

        // 2. Index komposit pada ratings untuk query testimoni & ulasan publik
        Schema::table('ratings', function (Blueprint $table) {
            $table->index(['is_tampil', 'created_at'], 'idx_ratings_is_tampil_created');
            $table->index(['is_tampil', 'id_layanan'], 'idx_ratings_is_tampil_layanan');
        });

        // 3. Index komposit pada keranjangs untuk query counter badge header
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->index(['id_user', 'status'], 'idx_keranjangs_user_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('user_id', 'bookings_user_id_index');
            $table->dropIndex('idx_bookings_user_status');
            $table->dropIndex('idx_bookings_user_booking_date');
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex('idx_ratings_is_tampil_created');
            $table->dropIndex('idx_ratings_is_tampil_layanan');
        });

        Schema::table('keranjangs', function (Blueprint $table) {
            $table->dropIndex('idx_keranjangs_user_status');
        });
    }
};
