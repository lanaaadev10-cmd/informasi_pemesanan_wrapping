<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Pembayaran (Transaksi Pembayaran Pesanan)
 *
 * @property int $id_pembayaran Primary key pembayaran
 * @property int $id_pesanan Foreign key ke tabel pesanans
 * @property \App\Enums\PaymentMethod|string $metode_pembayaran Metode pembayaran
 * @property float $jumlah_bayar Nominal yang dibayarkan
 * @property string|null $bukti_transfer Path berkas bukti transfer di storage
 * @property \App\Enums\PaymentStatus|string $status Status pembayaran
 * @property \Carbon\Carbon|null $tgl_bayar Tanggal dan jam pembayaran dilakukan
 * @property string $verifikasi_admin Status verifikasi admin: menunggu, diverifikasi, ditolak
 * @property string|null $catatan_admin Catatan admin terhadap verifikasi bukti
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Pesanan $pesanan
 */
class Pembayaran extends Model
{
    protected $table = 'pembayarans';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pesanan', 'metode_pembayaran', 'jumlah_bayar',
        'bukti_transfer', 'status', 'tgl_bayar',
        'verifikasi_admin', 'catatan_admin',
    ];

    protected $casts = [
        'status' => \App\Enums\PaymentStatus::class,
        'metode_pembayaran' => \App\Enums\PaymentMethod::class,
        'jumlah_bayar' => 'decimal:2',
        'tgl_bayar' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function isVerified(): bool
    {
        return $this->verifikasi_admin === 'diverifikasi';
    }

    public function isPending(): bool
    {
        return $this->verifikasi_admin === 'menunggu';
    }
}
