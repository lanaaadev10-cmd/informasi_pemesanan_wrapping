<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // STEP 1: Ubah kolom status dari ENUM lama ke ENUM baru yang mendukung semua nilai
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM(
            'menunggu_konfirmasi_admin',
            'menunggu_pembayaran',
            'menunggu_verifikasi_pembayaran',
            'dikonfirmasi',
            'sedang_diproses',
            'selesai',
            'ditolak',
            -- Nilai lama untuk kompatibilitas data yang mungkin ada
            'menunggu_verifikasi',
            'menunggu_konfirmasi',
            'dibayar',
            'diverifikasi',
            'perlu_diperbaiki',
            'dibatalkan'
        ) NOT NULL DEFAULT 'menunggu_konfirmasi_admin'");

        // STEP 2: Migrasi data lama ke nilai baru
        $statusMap = [
            'menunggu_verifikasi'  => 'menunggu_konfirmasi_admin',
            'diverifikasi'         => 'menunggu_pembayaran',
            'menunggu_konfirmasi'  => 'menunggu_verifikasi_pembayaran',
            'dibayar'              => 'dikonfirmasi',
            'perlu_diperbaiki'     => 'ditolak',
            'dibatalkan'           => 'ditolak',
        ];

        foreach ($statusMap as $old => $new) {
            DB::table('pesanans')->where('status', $old)->update(['status' => $new]);
        }

        // STEP 3: Bersihkan ENUM dari nilai lama yang tidak diperlukan lagi
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM(
            'menunggu_konfirmasi_admin',
            'menunggu_pembayaran',
            'menunggu_verifikasi_pembayaran',
            'dikonfirmasi',
            'sedang_diproses',
            'selesai',
            'ditolak'
        ) NOT NULL DEFAULT 'menunggu_konfirmasi_admin'");
    }

    public function down(): void
    {
        // Kembalikan ke ENUM lama
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM(
            'menunggu_verifikasi',
            'perlu_diperbaiki',
            'diverifikasi',
            'menunggu_pembayaran',
            'menunggu_konfirmasi',
            'dibayar',
            'selesai',
            'dibatalkan'
        ) NOT NULL DEFAULT 'menunggu_verifikasi'");
    }
};
