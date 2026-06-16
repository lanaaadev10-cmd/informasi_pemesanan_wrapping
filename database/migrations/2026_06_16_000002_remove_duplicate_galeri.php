<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $keepIds = [];
        $seen = [];

        DB::table('galeris')->orderBy('id_galeri')->get()->each(function ($g) use (&$keepIds, &$seen) {
            $key = $g->judul . '|' . $g->foto;
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $keepIds[] = $g->id_galeri;
            }
        });

        DB::table('galeris')->whereNotIn('id_galeri', $keepIds)->delete();
    }

    public function down(): void
    {
    }
};
