<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model MediaPenilaian (Foto / Dokumentasi Hasil Pengerjaan pada Ulasan Pelanggan)
 *
 * @property int $id
 * @property int $id_rating Foreign key ke tabel ratings
 * @property string $path Path file foto bukti di storage
 * @property int $urutan Urutan tampilan gambar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Penilaian $penilaian
 */
class MediaPenilaian extends Model
{
    use HasFactory;

    protected $table = 'rating_medias';

    protected $fillable = [
        'id_rating',
        'path',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class, 'id_rating', 'id');
    }

    public function rating(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class, 'id_rating', 'id');
    }
}
