<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model BookingPayment (Pembayaran untuk Booking Jadwal)
 *
 * @property int $id Primary key pembayaran booking
 * @property int $booking_id Foreign key ke tabel bookings
 * @property string $payment_method Metode transfer pembayaran (BCA, Mandiri, dll)
 * @property float $amount Nominal yang dibayarkan
 * @property string|null $proof_file Path file foto bukti transfer di storage
 * @property string $status Status verifikasi: pending, verified, rejected
 * @property \Carbon\Carbon|null $verified_at Waktu verifikasi oleh admin
 * @property string|null $admin_notes Catatan hasil verifikasi admin
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Booking $booking
 */
class BookingPayment extends Model
{
    protected $table = 'booking_payments';

    protected $fillable = [
        'booking_id',
        'payment_method',
        'amount',
        'proof_file',
        'status',
        'verified_at',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
