<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_pesanans', function (Blueprint $table) {
            // Data Kendaraan
            if (!Schema::hasColumn('form_pesanans', 'model_kendaraan')) {
                $table->string('model_kendaraan', 150)->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('form_pesanans', 'warna_kendaraan')) {
                $table->string('warna_kendaraan', 100)->nullable()->after('model_kendaraan');
            }
            if (!Schema::hasColumn('form_pesanans', 'nomor_polisi')) {
                $table->string('nomor_polisi', 50)->nullable()->after('warna_kendaraan');
            }
            if (!Schema::hasColumn('form_pesanans', 'tahun_produksi')) {
                $table->string('tahun_produksi', 20)->nullable()->after('nomor_polisi');
            }

            // Jadwal & Lokasi Pengerjaan
            if (!Schema::hasColumn('form_pesanans', 'lokasi_pengerjaan')) {
                $table->string('lokasi_pengerjaan', 150)->nullable()->after('tahun_produksi')
                    ->comment('Menyimpan jenis atau nama workshop (Toko / Home Service)');
            }
            if (!Schema::hasColumn('form_pesanans', 'jadwal_pengerjaan')) {
                $table->dateTime('jadwal_pengerjaan')->nullable()->after('lokasi_pengerjaan')
                    ->comment('Menyimpan tanggal mulai sesi pengerjaan');
            }
            if (!Schema::hasColumn('form_pesanans', 'estimasi_durasi')) {
                $table->string('estimasi_durasi', 100)->nullable()->default('4 - 5 Hari Kerja')->after('jadwal_pengerjaan')
                    ->comment('Menyimpan estimasi durasi pengerjaan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('form_pesanans', function (Blueprint $table) {
            $cols = [
                'model_kendaraan', 'warna_kendaraan', 'nomor_polisi', 'tahun_produksi',
                'lokasi_pengerjaan', 'jadwal_pengerjaan', 'estimasi_durasi'
            ];
            $existing = array_filter($cols, fn($c) => Schema::hasColumn('form_pesanans', $c));
            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
