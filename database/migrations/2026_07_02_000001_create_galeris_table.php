<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeris', function (Blueprint $table) {
            $table->id('id_galeri');
            $table->string('judul');
            $table->string('foto');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_upload');
            $table->string('kategori')->nullable();
            $table->string('sub_judul')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('badge_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};
