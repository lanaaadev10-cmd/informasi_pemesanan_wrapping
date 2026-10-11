<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('vehicle_type', ['motor', 'mobil'])->default('mobil');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['vehicle_type', 'is_active']);
        });

        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('vehicle_brands')->cascadeOnDelete();
            $table->string('name', 100);
            $table->enum('vehicle_size', ['small', 'medium', 'large', 'xlarge'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['brand_id', 'vehicle_size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_models');
        Schema::dropIfExists('vehicle_brands');
    }
};
