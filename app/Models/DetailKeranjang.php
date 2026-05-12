<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKeranjang extends Model
{
    protected $table = 'detail_keranjangs';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_keranjang',
        'id_paket',
        'jumlah',
        'catatan_custom',
        'harga_satuan',
        'subtotal',
    ];

    // Relasi ke Keranjang
    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'id_keranjang', 'id_keranjang');
    }

    // Relasi ke Layanan (Paket)
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_paket', 'id');
    }
}
