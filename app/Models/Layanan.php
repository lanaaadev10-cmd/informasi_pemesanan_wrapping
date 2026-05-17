<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'tipe_layanan',
    ];
}
