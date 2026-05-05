<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('profil_perusahaans', function (Blueprint $table) {
        $table->id();
        $table->string('nama_perusahaan');
        $table->text('deskripsi');
        $table->string('alamat');
        $table->string('email');
        $table->string('nomor_telepon');
        $table->string('logo')->nullable();
        $table->text('maps_url')->nullable();
        $table->timestamps();
});
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_perusahaans');
    }
};
