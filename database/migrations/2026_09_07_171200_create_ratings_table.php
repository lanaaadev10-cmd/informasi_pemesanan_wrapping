<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('id_pesanan')->nullable()->constrained('pesanans', 'id_pesanan')->nullOnDelete();
            $table->foreignId('id_layanan')->constrained('layanans', 'id_layanan')->cascadeOnDelete();
            $table->tinyInteger('rating');
            $table->text('ulasan')->nullable();
            $table->boolean('is_tampil')->default(true);
            // order_ref: diset otomatis oleh aplikasi menjadi id_pesanan (atau 0 saat id_pesanan NULL).
            // Dipakai penegak unique utk Alur 2 karena MySQL memperlakukan NULL sbg nilai yang selalu unik.
            $table->unsignedBigInteger('order_ref')->default(0);
            $table->timestamps();

            // Alur 1: 1 rating per layanan dalam 1 pesanan (id_pesanan tidak NULL, jadi unique langsung valid)
            $table->unique(['id_pesanan', 'id_layanan']);

            // Alur 2: 1 rating per layanan per akun tanpa pesanan (order_ref = 0)
            $table->unique(['id_user', 'id_layanan', 'order_ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
