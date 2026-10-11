<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model TambahanReservasi (Rincian Layanan Tambahan pada Reservasi Workshop)
 *
 * @property int $id
 * @property int $booking_id Foreign key ke reservasi
 * @property int|null $addon_id Foreign key ke master tambahan
 * @property string $addon_name Snapshot nama layanan tambahan
 * @property float $price_snapshot Snapshot harga saat reservasi dibuat
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Reservasi $reservasi
 * @property-read Tambahan|null $tambahan
 */
class TambahanReservasi extends Model
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

    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class, 'booking_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class, 'booking_id');
    }

    public function tambahan(): BelongsTo
    {
        return $this->belongsTo(Tambahan::class, 'addon_id');
    }

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Tambahan::class, 'addon_id');
    }
}
