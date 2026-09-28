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
        Schema::table('pesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanans', 'booking_date')) {
                $table->date('booking_date')->nullable()->after('tanggal_pesan');
            }
            if (!Schema::hasColumn('pesanans', 'created_by_admin_id')) {
                $table->foreignId('created_by_admin_id')->nullable()->after('booking_date')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->dropColumn(['booking_date', 'created_by_admin_id']);
        });
    }
};
