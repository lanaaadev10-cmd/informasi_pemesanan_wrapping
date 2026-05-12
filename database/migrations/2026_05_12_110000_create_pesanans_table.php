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
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->string('kode_pesanan')->unique();
            $table->date('tanggal_pesan');
            $table->enum('status', [
                'menunggu_verifikasi',
                'perlu_diperbaiki',
                'diverifikasi',
                'menunggu_pembayaran',
                'dibayar',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi');
            $table->text('catatan_admin')->nullable();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
