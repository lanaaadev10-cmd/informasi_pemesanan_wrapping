<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profil_perusahaan_id')->default(1);
            $table->foreign('profil_perusahaan_id')->references('id')->on('profil_perusahaans')->onDelete('cascade');
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->text('bio')->nullable();
            $table->string('foto')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
