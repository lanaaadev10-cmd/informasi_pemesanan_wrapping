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

            // Data Kendaraan
            $table->string('model_kendaraan', 150)->nullable();
            $table->string('warna_kendaraan', 100)->nullable();
            $table->string('nomor_polisi', 50)->nullable();
            $table->string('tahun_produksi', 20)->nullable();

            // Jadwal & Lokasi Pengerjaan
            $table->string('lokasi_pengerjaan', 150)->nullable();
            $table->dateTime('jadwal_pengerjaan')->nullable();
            $table->string('estimasi_durasi', 100)->nullable()->default('4 - 5 Hari Kerja');

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
