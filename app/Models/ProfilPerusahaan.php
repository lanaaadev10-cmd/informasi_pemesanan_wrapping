<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfilPerusahaan extends Model
{
    protected $table = 'profil_perusahaans';

    protected $guarded = [];

    public function teamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'profil_perusahaan_id');
    }
}
