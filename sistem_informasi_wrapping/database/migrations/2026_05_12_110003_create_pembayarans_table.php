<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_pesanan')->constrained('pesanans', 'id_pesanan')->onDelete('cascade');
            $table->string('metode_pembayaran');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('bukti_transfer')->nullable(); // path file foto
            $table->enum('status', [
                'menunggu_pembayaran',
                'sudah_dibayar',
                'ditolak',
            ])->default('menunggu_pembayaran');
            $table->date('tgl_bayar')->nullable();
            $table->enum('verifikasi_admin', [
                'menunggu',
                'diverifikasi',
                'ditolak',
            ])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
