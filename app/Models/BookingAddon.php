<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Detail Addon pada Booking
 *
 * @property int $id
 * @property int $booking_id
 * @property int|null $addon_id
 * @property string $addon_name
 * @property float $price_snapshot
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Booking $booking
 * @property-read Addon|null $addon
 */
class BookingAddon extends Model
{
    use HasFactory;

    protected $table = 'booking_addons';

    protected $fillable = [
        'booking_id',
        'addon_id',
        'addon_name',
        'price_snapshot',
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class, 'addon_id');
    }
}
