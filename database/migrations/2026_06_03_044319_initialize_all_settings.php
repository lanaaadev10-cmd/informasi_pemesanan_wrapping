<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settingsData = [
            'galeri' => [
                'galeri_hero_title',
                'galeri_hero_desc',
                'galeri_intro_text',
                'galeri_empty_state_title',
                'galeri_empty_state_desc',
                'galeri_filter_all_label',
                'galeri_filter_categories',
            ],
        ];

        foreach ($settingsData as $group => $properties) {
            foreach ($properties as $property) {
                DB::table('settings')->updateOrInsert(
                    ['group' => $group, 'name' => $property],
                    [
                        'payload' => json_encode(null),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        // Optionally delete settings if needed
    }
};
