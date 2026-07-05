<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'nama', 'jabatan', 'bio', 'foto', 'instagram', 'linkedin', 'urutan', 'is_aktif',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'is_aktif' => 'boolean',
    ];

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
