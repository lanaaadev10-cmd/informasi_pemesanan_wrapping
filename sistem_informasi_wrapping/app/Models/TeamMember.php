<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    protected $fillable = [
        'profil_perusahaan_id', 'nama', 'jabatan', 'bio', 'foto', 'instagram', 'linkedin', 'urutan', 'is_aktif',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'is_aktif' => 'boolean',
    ];

    public function profilPerusahaan(): BelongsTo
    {
        return $this->belongsTo(ProfilPerusahaan::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}

