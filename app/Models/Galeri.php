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
        'kategori',
        'jenis',
        'is_featured',
        'badge_text',
    ];

    protected static function booted()
    {
        static::saved(function () {
            \App\Services\CacheService::clearGaleriCaches();
        });
        static::deleted(function () {
            \App\Services\CacheService::clearGaleriCaches();
        });
    }
}
