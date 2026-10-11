<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

/**
 * Model Penilaian (Ulasan & Rating Bintang Kepuasan Pelanggan)
 *
 * @property int $id
 * @property int $id_user
 * @property int|null $id_pesanan
 * @property int|null $booking_id
 * @property int|null $id_layanan
 * @property int $rating Skala bintang 1 sampai 5
 * @property string|null $ulasan Testimoni tertulis pelanggan
 * @property string|null $balasan_admin Tanggapan resmi dari workshop
 * @property \Carbon\Carbon|null $dibalas_at Waktu admin membalas
 * @property bool $is_tampil Status tampil di halaman publik /testimoni
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Pesanan|null $pesanan
 * @property-read Booking|null $booking
 * @property-read Layanan|null $layanan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MediaPenilaian> $medias
 */
class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'id_user',
        'id_pesanan',
        'booking_id',
        'id_layanan',
        'rating',
        'ulasan',
        'balasan_admin',
        'dibalas_at',
        'is_tampil',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_tampil' => 'boolean',
        'dibalas_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('testimoni_ratings');
        });
        static::deleted(function () {
            Cache::forget('testimoni_ratings');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class, 'booking_id', 'id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    public function medias(): HasMany
    {
        return $this->hasMany(MediaPenilaian::class, 'id_rating', 'id');
    }

    public function getTipeRatingAttribute(): string
    {
        if ($this->booking_id) {
            return 'Booking';
        }
        return $this->id_pesanan ? 'Pesanan' : 'Layanan';
    }
}
