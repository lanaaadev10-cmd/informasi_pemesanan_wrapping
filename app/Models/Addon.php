<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Master Layanan Tambahan (Add-on)
 *
 * @property int $id
 * @property string $name
 * @property string $category 'motor', 'mobil', 'all'
 * @property float $price
 * @property string|null $description
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Addon extends Model
{
    use HasFactory;

    protected $table = 'addons';

    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function bookingAddons(): HasMany
    {
        return $this->hasMany(BookingAddon::class, 'addon_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
