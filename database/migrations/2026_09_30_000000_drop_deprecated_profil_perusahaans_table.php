<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menghapus tabel profil_perusahaans yang sudah deprecated (0 baris, tanpa model Eloquent),
     * karena seluruh konfigurasi perusahaan sudah dipindahkan ke tabel Spatie settings (CompanySettings).
     */
    public function up(): void
    {
        Schema::dropIfExists('profil_perusahaans');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('profil_perusahaans')) {
            Schema::create('profil_perusahaans', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('nama_perusahaan', 150)->nullable();
                $table->text('deskripsi')->nullable();
                $table->text('alamat')->nullable();
                $table->string('email', 100)->nullable();
                $table->string('nomor_telepon', 50)->nullable();
                $table->text('logo')->nullable();
                $table->text('maps_url')->nullable();
            });
        }
    }
};
