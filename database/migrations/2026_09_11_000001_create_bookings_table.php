<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('layanans', 'id_layanan')->cascadeOnDelete();
            $table->date('booking_date');
            $table->enum('payment_type', ['dp', 'lunas']);
            $table->string('vehicle_name', 150);
            $table->string('vehicle_color', 100)->nullable();
            $table->string('vehicle_license', 50)->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 64)->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('booking_date');
            $table->index('status');
            $table->index(['booking_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
