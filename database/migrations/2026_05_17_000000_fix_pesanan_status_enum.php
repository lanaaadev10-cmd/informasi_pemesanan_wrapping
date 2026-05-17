<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $statuses = [
                'menunggu_verifikasi',
                'perlu_diperbaiki',
                'diverifikasi',
                'menunggu_pembayaran',
                'menunggu_konfirmasi',
                'dibayar',
                'selesai',
                'dibatalkan',
            ];

            $statusList = implode("','", $statuses);

            // For MySQL, recreate the enum
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE pesanans MODIFY status ENUM('" . $statusList . "') DEFAULT 'menunggu_verifikasi'");
            }
            // For SQLite, use string type
            elseif (DB::getDriverName() === 'sqlite') {
                // SQLite doesn't support enums, already using string
                // This migration just documents the change
            }
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $statuses = [
                'menunggu_verifikasi',
                'perlu_diperbaiki',
                'diverifikasi',
                'menunggu_pembayaran',
                'dibayar',
                'selesai',
                'dibatalkan',
            ];

            $statusList = implode("','", $statuses);

            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE pesanans MODIFY status ENUM('" . $statusList . "') DEFAULT 'menunggu_verifikasi'");
            }
        });
    }
};
