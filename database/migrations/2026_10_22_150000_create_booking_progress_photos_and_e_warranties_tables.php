<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Foto Dokumentasi Progres Pengerjaan Mobil
        Schema::create('booking_progress_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('stage', 100); // e.g. "Pembersihan & Detailing", "Pemasangan Vinyl", "Finishing & QC"
            $table->string('photo_path', 255);
            $table->text('caption')->nullable();
            $table->foreignId('uploaded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('booking_id');
        });

        // 2. Tabel Sertifikat Garansi Digital (E-Warranty)
        Schema::create('e_warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->string('warranty_code', 50)->unique(); // e.g. WRN-20261022-ABCD
            $table->string('warranty_type', 100)->default('Garansi Lem & Gelembung Resmi Dantie');
            $table->integer('duration_months')->default(12); // Default 1 tahun
            $table->date('start_date');
            $table->date('end_date');
            $table->text('terms')->nullable();
            $table->enum('status', ['active', 'claimed', 'expired'])->default('active');
            $table->timestamps();

            $table->index(['booking_id', 'status']);
            $table->index('warranty_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_warranties');
        Schema::dropIfExists('booking_progress_photos');
    }
};
