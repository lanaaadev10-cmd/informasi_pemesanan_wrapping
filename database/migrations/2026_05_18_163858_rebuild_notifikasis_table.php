<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('notifikasis');

        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id('id_notif');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_pesanan')->nullable()->constrained('pesanans', 'id_pesanan')->onDelete('set null');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['info', 'pesanan', 'pembayaran', 'sistem', 'email', 'sms', 'in_app', 'push'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');

        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id('id_notif');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_pesanan')->nullable()->constrained('pesanans', 'id_pesanan')->onDelete('set null');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['info', 'pesanan', 'pembayaran', 'sistem'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }
};
