<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model DetailPesanan (Item Layanan dalam Pesanan)
 *
 * @property int $id_detail Primary key detail pesanan
 * @property int $id_pesanan Foreign key ke tabel pesanans
 * @property int $id_paket Foreign key ke tabel layanans (id_layanan)
 * @property int $jumlah Kuantitas / unit
 * @property string|null $catatan_custom Catatan kustomisasi pengerjaan
 * @property float $harga_satuan Harga per item saat pesanan dibuat
 * @property float $subtotal Total harga baris (jumlah * harga_satuan)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Pesanan $pesanan
 * @property-read Layanan $layanan
 */
class DetailPesanan extends Model
{
    protected $table = 'detail_pesanans';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_pesanan', 'id_paket', 'jumlah',
        'catatan_custom', 'harga_satuan', 'subtotal',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_paket', 'id_layanan');
    }
}
