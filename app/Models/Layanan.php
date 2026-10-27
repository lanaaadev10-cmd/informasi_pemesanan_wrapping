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
            \App\Services\CacheService::clearLayananCaches();
        });
        static::deleted(function () {
            \App\Services\CacheService::clearLayananCaches();
        });
        static::restored(function () {
            \App\Services\CacheService::clearLayananCaches();
        });
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Dapatkan 4 Pilar Kebutuhan Layanan (2-Tier Cascading Selector)
     * 1. jasa-pasang : Jasa pemasangan wrapping / decal (bawa bahan sendiri)
     * 2. full-wrap   : Paket wrapping bodi kendaraan menyeluruh bergaransi
     * 3. custom      : Desain custom, striping, cutting, partial
     * 4. aksesoris   : Kaca film, audio, lighting/biled, variasi
     */
    public function getPillarAttribute(): string
    {
        $catSlug = strtolower(trim($this->tipe_paket ?? ''));
        $jenis   = strtolower(trim($this->jenis_layanan ?? ''));
        $name    = strtolower(trim($this->nama_layanan ?? ''));

        if ($jenis === 'jasa_pasang' || str_contains($catSlug, 'jasa-pasang') || str_contains($name, 'jasa pasang')) {
            return 'jasa-pasang';
        }

        if (in_array($catSlug, ['custom', 'striping', 'partial']) || str_contains($name, 'custom') || str_contains($name, 'striping')) {
            return 'custom';
        }

        if (in_array($catSlug, ['window-film', 'lighting', 'audio', 'aksesoris']) || str_contains($name, 'kaca film') || str_contains($name, 'film') || str_contains($name, 'biled') || str_contains($name, 'lampu') || str_contains($name, 'audio')) {
            return 'aksesoris';
        }

        return 'full-wrap';
    }

    /**
     * Label representatif untuk 4 pilar kebutuhan
     */
    public function getPillarLabelAttribute(): string
    {
        return match ($this->pillar) {
            'jasa-pasang' => 'Jasa Pasang',
            'full-wrap'   => 'Full Wrap',
            'custom'      => 'Custom',
            'aksesoris'   => 'Aksesoris',
            default       => 'Layanan',
        };
    }

    /**
     * Warna badge representatif untuk 4 pilar kebutuhan
     */
    public function getPillarBadgeColorAttribute(): string
    {
        return match ($this->pillar) {
            'jasa-pasang' => 'bg-emerald-600/90 text-white',
            'full-wrap'   => 'bg-blue-600/90 text-white',
            'custom'      => 'bg-purple-600/90 text-white',
            'aksesoris'   => 'bg-amber-600/90 text-white',
            default       => 'bg-slate-700/90 text-white',
        };
    }

    /**
     * Cek apakah pilar diarahkan khusus melalui WhatsApp (tanpa direct checkout/cart)
     */
    public function isWhatsappOnly(): bool
    {
        return in_array($this->pillar, ['jasa-pasang', 'custom']);
    }
}

