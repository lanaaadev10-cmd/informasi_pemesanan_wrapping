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
            // 1. Tambah index biasa untuk id_user agar foreign key constraint tetap valid saat unique index dicabut
            $table->index('id_user', 'ratings_id_user_index');

            // 2. Cabut unique constraint lama (id_user, id_layanan, order_ref)
            // yang menyebabkan tabrakan saat user mengulas booking berulang (repeat order)
            $table->dropUnique('ratings_id_user_id_layanan_order_ref_unique');

            // 3. Hapus kolom legacy order_ref
            $table->dropColumn('order_ref');

            // 4. Tambah index untuk query testimoni publik & filtering admin
            $table->index(['is_tampil', 'created_at'], 'ratings_is_tampil_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex('ratings_is_tampil_created_at_index');
            $table->unsignedBigInteger('order_ref')->default(0)->after('is_tampil');
            $table->unique(['id_user', 'id_layanan', 'order_ref'], 'ratings_id_user_id_layanan_order_ref_unique');
            $table->dropIndex('ratings_id_user_index');
        });
    }
};
