<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ModelKendaraan (Tipe / Model Kendaraan dengan Spesifikasi Ukuran)
 *
 * @property int $id
 * @property int $brand_id
 * @property string $name
 * @property string $vehicle_size 'small', 'medium', 'large', 'xlarge'
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read MerekKendaraan $brand
 */
class ModelKendaraan extends Model
{
    use HasFactory;

    protected $table = 'vehicle_models';

    protected $fillable = [
        'brand_id',
        'name',
        'vehicle_size',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(MerekKendaraan::class, 'brand_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getSizeLabelAttribute(): string
    {
        return match ($this->vehicle_size) {
            'small'  => 'Kecil (Small)',
            'medium' => 'Sedang (Medium)',
            'large'  => 'Besar (Large)',
            'xlarge' => 'Ekstra Besar (XL)',
            default  => ucfirst($this->vehicle_size),
        };
    }
}
