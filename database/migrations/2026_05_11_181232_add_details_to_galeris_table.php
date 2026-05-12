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
        Schema::table('galeris', function (Blueprint $table) {
            // Baru saya tambah: Kontrol tampilan dinamis dari CMS
            $table->boolean('is_featured')->default(false)->after('foto');
            $table->string('sub_judul')->nullable()->after('judul');
            $table->string('badge_text')->default('Featured Project')->after('foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'sub_judul', 'badge_text']);
        });
    }
};
