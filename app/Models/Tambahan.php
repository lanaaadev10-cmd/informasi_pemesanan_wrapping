<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Tambahan (Layanan / Aksesoris Tambahan Workshop)
 *
 * @property int $id
 * @property string $name Nama layanan tambahan (contoh: Coating Kaca, Detailing Mesin)
 * @property string $category 'motor', 'mobil', 'all'
 * @property float $price Harga tambahan
 * @property string|null $description Deskripsi manfaat layanan tambahan
 * @property bool $is_active Status aktif
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Tambahan extends Model
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

    public function tambahanReservasis(): HasMany
    {
        return $this->hasMany(TambahanReservasi::class, 'addon_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
