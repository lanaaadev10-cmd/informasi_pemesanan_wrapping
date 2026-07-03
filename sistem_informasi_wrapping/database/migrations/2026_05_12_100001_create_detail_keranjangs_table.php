<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_keranjangs', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_keranjang')->constrained('keranjangs', 'id_keranjang')->onDelete('cascade');
            $table->foreignId('id_paket')->constrained('layanans', 'id_layanan')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->text('catatan_custom')->nullable();
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_keranjangs');
    }
};
