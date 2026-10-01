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
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('booking_id')
                ->nullable()
                ->after('id_pesanan')
                ->constrained('bookings', 'id')
                ->cascadeOnDelete();

            $table->unique(['booking_id', 'id_layanan'], 'ratings_booking_layanan_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropUnique('ratings_booking_layanan_unique');
            $table->dropColumn('booking_id');
        });
    }
};
