<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanans', function (Blueprint $table) {
            $table->id('id_layanan');
            $table->string('nama_layanan');
            $table->string('tipe_paket')->nullable();
            $table->text('deskripsi')->nullable();
            $table->json('fitur')->nullable();
            $table->integer('harga')->nullable();
            $table->string('kategori')->default('mobil');
            $table->string('estimasi_waktu', 100)->nullable();
            $table->enum('tipe_layanan', ['fix', 'custom']);
            $table->string('foto_contoh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
