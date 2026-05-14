<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Models\Traits\HasCompanyCms;

class ProfilPerusahaan extends Model
{
    use HasCompanyCms;

    /**
     * Data Identitas Inti Perusahaan.
     * Kolom CMS lainnya dipindahkan ke Trait HasCompanyCms agar tidak menumpuk.
     */
    protected $fillable = [
        'nama_perusahaan',
        'deskripsi',
        'alamat',
        'email',
        'nomor_telepon',
        'logo',
        'maps_url',
    ];

    protected $casts = [
        'testimonis_json' => 'array',
        'about_feature_list' => 'array',
    ];

    /**
     * Gabungkan fillable dari Trait saat model diinisialisasi.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->mergeFillable($this->getCmsFillable());
    }

    protected static function booted()
    {
        static::saved(fn () => Cache::forget('site_profile'));
        static::deleted(fn () => Cache::forget('site_profile'));
    }
}
