<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'id_user', 'kode_pesanan', 'tanggal_pesan',
        'status', 'catatan_admin', 'total_harga',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function form()
    {
        return $this->hasOne(FormPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_pesanan', 'id_pesanan');
    }
}
