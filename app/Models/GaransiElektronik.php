<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model GaransiElektronik (E-Warranty Sertifikat Resmi Pengerjaan Wrapping)
 *
 * @property int $id
 * @property int $booking_id ID Reservasi terkait
 * @property string $warranty_code Kode unik sertifikat garansi
 * @property string $warranty_type Jenis garansi
 * @property int $duration_months Masa berlaku (bulan)
 * @property \Carbon\Carbon $start_date Tanggal mulai garansi
 * @property \Carbon\Carbon $end_date Tanggal berakhir garansi
 * @property string|null $terms Syarat & ketentuan klaim garansi
 * @property string $status Status garansi (active, expired, void)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Reservasi $reservasi
 * @property-read int $sisa_hari
 */
class GaransiElektronik extends Model
{
    use HasFactory;

    protected $table = 'e_warranties';

    protected $fillable = [
        'booking_id',
        'warranty_code',
        'warranty_type',
        'duration_months',
        'start_date',
        'end_date',
        'terms',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /**
     * Relasi ke Reservasi (Bahasa Indonesia)
     */
    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class, 'booking_id');
    }

    /**
     * Kompatibilitas relasi booking
     */
    public function booking(): BelongsTo
    {
        return $this->reservasi();
    }

    /**
     * Cek apakah masa berlaku garansi sudah habis
     */
    public function apakahKadaluarsa(): bool
    {
        return $this->end_date->isPast();
    }

    /**
     * Kompatibilitas cek masa berlaku
     */
    public function isExpired(): bool
    {
        return $this->apakahKadaluarsa();
    }

    /**
     * Accessor sisa hari aktif garansi
     */
    public function getSisaHariAttribute(): int
    {
        return $this->getRemainingDaysAttribute();
    }

    /**
     * Accessor sisa hari aktif garansi (Kompatibilitas)
     */
    public function getRemainingDaysAttribute(): int
    {
        if ($this->apakahKadaluarsa()) {
            return 0;
        }

        return (int) now()->diffInDays($this->end_date, false);
    }
}
