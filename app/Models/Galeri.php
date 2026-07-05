<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_galeri';

    protected $fillable = [
        'judul',
        'foto',
        'deskripsi',
        'tanggal_upload',
        'sub_judul',
        'is_featured',
        'badge_text',
    ];
}
