<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string('kode_pesanan')->unique();
            $table->date('tanggal_pesan');
            $table->string('status', 64)->default('menunggu_konfirmasi_admin');
            $table->text('catatan_admin')->nullable();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->string('order_source', 20)->default('online');
            $table->string('customer_name', 255)->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
