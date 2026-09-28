<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'booking_code',
        'user_id',
        'layanan_id',
        'booking_date',
        'payment_type',
        'vehicle_name',
        'vehicle_color',
        'vehicle_license',
        'notes',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date:Y-m-d',
            'status' => BookingStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'id_layanan');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(BookingPayment::class);
    }

    public function getLabelStatusAttribute(): string
    {
        return $this->status->label();
    }

    public function getWarnaBadgeAttribute(): string
    {
        return $this->status->badgeColor();
    }

    public function bisaUploadBukti(): bool
    {
        return $this->status->canUploadPayment();
    }

    public function bisaDibatalkan(): bool
    {
        return $this->status->canBeCancelled();
    }

    protected static function booted(): void
    {
        static::saved(function (Booking $booking) {
            \Cache::forget("booking_quota_{$booking->booking_date->format('Y-m-d')}");
        });

        static::deleted(function (Booking $booking) {
            \Cache::forget("booking_quota_{$booking->booking_date->format('Y-m-d')}");
        });
    }
}
