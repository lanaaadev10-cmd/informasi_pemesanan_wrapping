<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            if (!Schema::hasColumn('layanans', 'estimasi_waktu')) {
                $table->string('estimasi_waktu', 100)->nullable()->after('kategori')
                    ->comment('Menyimpan estimasi waktu pengerjaan paket layanan (misal: 3 Hari Kerja)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            if (Schema::hasColumn('layanans', 'estimasi_waktu')) {
                $table->dropColumn('estimasi_waktu');
            }
        });
    }
};
