<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rating_medias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rating')->constrained('ratings', 'id')->cascadeOnDelete();
            $table->string('path');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_medias');
    }
};
