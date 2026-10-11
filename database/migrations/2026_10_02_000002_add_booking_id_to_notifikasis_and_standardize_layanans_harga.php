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
        // 1. Tambah foreign key booking_id ke tabel notifikasis agar notifikasi booking terhubung langsung ke detail booking
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->foreignId('booking_id')
                ->nullable()
                ->after('id_pesanan')
                ->constrained('bookings', 'id')
                ->nullOnDelete();

            $table->index(['id_user', 'is_read'], 'notifikasis_id_user_is_read_index');
        });

        // 2. Standarisasi tipe data harga pada tabel layanans menjadi decimal(15,2) konsisten dengan tabel transaksi
        Schema::table('layanans', function (Blueprint $table) {
            $table->decimal('harga', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->integer('harga')->nullable()->change();
        });

        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropIndex('notifikasis_id_user_is_read_index');
            $table->dropColumn('booking_id');
        });
    }
};
