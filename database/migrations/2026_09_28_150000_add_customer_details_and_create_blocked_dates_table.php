<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom customer details & booking_time ke tabel bookings
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'customer_name')) {
                $table->string('customer_name', 150)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('bookings', 'customer_phone')) {
                $table->string('customer_phone', 30)->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('bookings', 'customer_email')) {
                $table->string('customer_email', 150)->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('bookings', 'booking_time')) {
                $table->string('booking_time', 10)->nullable()->after('booking_date');
            }
        });

        // Modifikasi kolom user_id dan vehicle_name agar nullable jika diperlukan
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('vehicle_name', 150)->nullable()->change();
            $table->enum('payment_type', ['dp', 'lunas'])->default('dp')->change();
        });

        // 2. Buat tabel blocked_dates untuk tanggal libur / diblokir admin
        if (!Schema::hasTable('blocked_dates')) {
            Schema::create('blocked_dates', function (Blueprint $table) {
                $table->id();
                $table->date('date')->unique();
                $table->string('reason', 255)->default('Tanggal ditutup / Libur');
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['date', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_dates');

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'customer_phone', 'customer_email', 'booking_time']);
        });
    }
};
