<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPerusahaan extends Model
{
    protected $fillable = [
    'nama_perusahaan',
    'deskripsi',
    'alamat',
    'email',
    'nomor_telepon',
    'logo',
];
}
