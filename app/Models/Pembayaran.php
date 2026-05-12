<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pesanan', 'metode_pembayaran', 'jumlah_bayar',
        'bukti_transfer', 'status', 'tgl_bayar',
        'verifikasi_admin', 'catatan_admin',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
