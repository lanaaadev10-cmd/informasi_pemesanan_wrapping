<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Layanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'tipe_layanan',
        'foto_contoh',
        'tipe_paket',
        'fitur',
        'kategori',
        'estimasi_waktu',
        'biaya_layanan',
        'biaya_layanan_label',
    ];

    protected $casts = [
        'fitur' => 'array',
        'biaya_layanan' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('site_layanans');
            Cache::forget('katalog_layanans');
            Cache::forget('dashboard_layanans');
        });
        static::deleted(function () {
            Cache::forget('site_layanans');
            Cache::forget('katalog_layanans');
            Cache::forget('dashboard_layanans');
        });
    }
}
