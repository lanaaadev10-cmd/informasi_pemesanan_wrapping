<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel `testimonis` legacy (Migrasi 2026_05_13_012118) sudah digantikan
     * penuh oleh tabel `ratings`. Fitur tidak lagi direferensikan oleh
     * controller/resource/view mana pun, sehingga tabel dibersihkan.
     */
    public function up(): void
    {
        Schema::dropIfExists('testimonis');
    }

    public function down(): void
    {
        // Tidak restore: tabel legacy tidak digunakan lagi.
    }
};