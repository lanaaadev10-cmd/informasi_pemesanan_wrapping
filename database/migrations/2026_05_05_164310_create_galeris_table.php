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
            $table->string('sub_judul')->nullable();
            $table->string('kategori')->nullable();
            $table->string('jenis', 100)->nullable();
            $table->string('foto');
            $table->string('badge_text')->default('Featured Project');
            $table->boolean('is_featured')->default(false);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_upload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};
