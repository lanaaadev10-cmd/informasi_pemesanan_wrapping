<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keranjangs', function (Blueprint $table) {
            $table->id('id_keranjang');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->enum('status', ['active', 'checked_out'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjangs');
    }
};
