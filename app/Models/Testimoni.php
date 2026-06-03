<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimoni extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jabatan',
        'isi_testimoni',
        'foto',
        'rating',
        'is_tampilkan',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_tampilkan' => 'boolean',
    ];
}
