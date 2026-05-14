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

    protected static function booted()
    {
        static::updated(function ($pesanan) {
            if ($pesanan->wasChanged('status')) {
                if ($pesanan->status === 'selesai') {
                    Notifikasi::create([
                        'id_user' => $pesanan->id_user,
                        'id_pesanan' => $pesanan->id_pesanan,
                        'judul' => 'Pemasangan Selesai!',
                        'pesan' => 'Halo! Proses pemasangan kendaraan Anda sudah selesai. Silahkan diambil di toko ya. Terima kasih!',
                        'tipe' => 'info',
                        'is_read' => false,
                    ]);
                } elseif ($pesanan->status === 'menunggu_pembayaran') {
                    Notifikasi::create([
                        'id_user' => $pesanan->id_user,
                        'id_pesanan' => $pesanan->id_pesanan,
                        'judul' => 'Pesanan Diverifikasi',
                        'pesan' => 'Pesanan Anda #' . $pesanan->kode_pesanan . ' telah diverifikasi Admin. Silahkan lakukan pembayaran untuk memproses pengerjaan.',
                        'tipe' => 'info',
                        'is_read' => false,
                    ]);
                }
            }
        });
    }
}
