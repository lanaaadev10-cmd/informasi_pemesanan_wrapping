<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $primaryKey = 'id_layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'gambar',
        'foto_contoh',
        'harga',
        'tipe_layanan',
        'tipe_paket',
        'fitur',
        'kategori',
    ];

    protected $casts = [
        'fitur' => 'array',
    ];
}
