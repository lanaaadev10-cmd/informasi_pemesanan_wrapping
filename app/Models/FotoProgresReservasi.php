<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Model FotoProgresReservasi (Dokumentasi Foto Pengerjaan Unit Workshop)
 *
 * @property int $id
 * @property int $booking_id ID Reservasi terkait
 * @property string $stage Tahapan pengerjaan (persiapan, pengerjaan, penyelesaian)
 * @property string $photo_path Lokasi file foto
 * @property string|null $caption Keterangan singkat foto
 * @property int|null $uploaded_by_id ID user/admin yang mengunggah
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Reservasi $reservasi
 * @property-read User|null $pengunggah
 * @property-read string $url_foto
 */
class FotoProgresReservasi extends Model
{
    use HasFactory;

    protected $table = 'booking_progress_photos';

    protected $fillable = [
        'booking_id',
        'stage',
        'photo_path',
        'caption',
        'uploaded_by_id',
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
     * Relasi ke Pengguna/Admin yang mengunggah foto
     */
    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }

    /**
     * Kompatibilitas relasi uploader
     */
    public function uploader(): BelongsTo
    {
        return $this->pengunggah();
    }

    /**
     * Accessor URL Foto dalam Bahasa Indonesia
     */
    public function getUrlFotoAttribute(): string
    {
        return $this->getPhotoUrlAttribute();
    }

    /**
     * Accessor URL Foto (Kompatibilitas)
     */
    public function getPhotoUrlAttribute(): string
    {
        if (!$this->photo_path) {
            return asset('images/layanan/wrapping-mobil.jpg');
        }

        return Storage::disk('public')->url($this->photo_path);
    }
}
