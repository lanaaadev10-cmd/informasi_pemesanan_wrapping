<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->text('balasan_admin')->nullable()->after('ulasan');
            $table->timestamp('dibalas_at')->nullable()->after('balasan_admin');
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn(['balasan_admin', 'dibalas_at']);
        });
    }
};