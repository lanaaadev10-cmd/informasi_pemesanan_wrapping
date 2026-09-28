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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_walk_in')->default(false)->after('remember_token');
            $table->foreignId('walk_in_created_by')->nullable()->after('is_walk_in')->constrained('users')->onDelete('set null');
            $table->text('walk_in_note')->nullable()->after('walk_in_created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['walk_in_created_by']);
            $table->dropColumn(['is_walk_in', 'walk_in_created_by', 'walk_in_note']);
        });
    }
};
