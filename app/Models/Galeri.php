<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $primaryKey = 'id_galeri';

    protected $fillable = [
        'judul',
        'foto',
        'deskripsi',
        'tanggal_upload',
    ];
}
