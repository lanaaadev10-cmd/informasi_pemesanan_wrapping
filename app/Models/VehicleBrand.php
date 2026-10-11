<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Master Merk Kendaraan
 *
 * @property int $id
 * @property string $name
 * @property string $vehicle_type 'motor' / 'mobil'
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, VehicleModel> $models
 */
class VehicleBrand extends Model
{
    use HasFactory;

    protected $table = 'vehicle_brands';

    protected $fillable = [
        'name',
        'vehicle_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class, 'brand_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
