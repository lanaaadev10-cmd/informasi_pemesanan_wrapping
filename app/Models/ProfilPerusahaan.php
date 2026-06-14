<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPerusahaan extends Model
{
    protected $table = 'profil_perusahaans';

    protected $guarded = ['id'];

    protected $casts = [
        'testimonis_json' => 'array',
        'tentang_kami_team_members' => 'array',
        'layanan_1_fitur' => 'array',
        'layanan_2_fitur' => 'array',
        'layanan_3_fitur' => 'array',
        'layanan_4_fitur' => 'array',
        'dashboard_member_progress' => 'integer',
        'tentang_kami_values_columns' => 'integer',
        'layanan_grid_columns' => 'integer',
        'tentang_kami_show_values' => 'boolean',
        'tentang_kami_show_history' => 'boolean',
        'tentang_kami_show_team' => 'boolean',
        'layanan_show_benefits' => 'boolean',
        'layanan_show_warranty' => 'boolean',
        'dark_mode' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
