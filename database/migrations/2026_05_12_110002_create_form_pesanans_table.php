<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_pesanans', function (Blueprint $table) {
            $table->id('id_form');
            $table->foreignId('id_pesanan')->constrained('pesanans', 'id_pesanan')->onDelete('cascade');
            $table->string('nama_pemesan');
            $table->text('alamat_pengiriman');
            $table->string('no_hp', 20);
            $table->text('keterangan_tambahan')->nullable();
            $table->enum('status_verifikasi', [
                'pending',
                'terverifikasi',
                'perlu_diperbaiki',
            ])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_pesanans');
    }
};
