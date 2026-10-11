<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * Model Layanan (Katalog Layanan / Jasa Wrapping)
 *
 * @property int $id_layanan Primary key layanan
 * @property string $nama_layanan Nama jenis layanan wrapping
 * @property string|null $deskripsi Deskripsi detail layanan
 * @property float $harga Harga dasar layanan
 * @property string|null $tipe_layanan Tipe layanan (contoh: Full Body, Partial, Interior)
 * @property string|null $tipe_paket Klasifikasi paket layanan
 * @property string|null $foto_contoh Path gambar foto contoh hasil pengerjaan
 * @property array|null $fitur Daftar keunggulan / fitur paket (JSON array)
 * @property string|null $kategori Kategori kendaraan / wrapping
 * @property string|null $estimasi_waktu Estimasi lama pengerjaan (contoh: 2 - 3 Hari)
 * @property bool $is_active Status ketersediaan layanan
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Rating> $ratings
 */
class Layanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'tipe_layanan',
        'jenis_layanan',
        'tipe_paket',
        'foto_contoh',
        'fitur',
        'kategori',
        'estimasi_waktu',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'fitur' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

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
        static::restored(function () {
            Cache::forget('site_layanans');
            Cache::forget('katalog_layanans');
            Cache::forget('dashboard_layanans');
        });
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'id_layanan', 'id_layanan');
    }
}
