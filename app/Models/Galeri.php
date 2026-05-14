<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Galeri extends Model
{
    protected $primaryKey = 'id_galeri';

    protected $fillable = [
        'judul',
        'foto',
        'deskripsi',
        'tanggal_upload',
        'kategori',
        'sub_judul',
        'is_featured',
        'badge_text',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('site_galeris');
            Cache::forget('dashboard_galeris');
        });
        static::deleted(function () {
            Cache::forget('site_galeris');
            Cache::forget('dashboard_galeris');
        });
    }
}
