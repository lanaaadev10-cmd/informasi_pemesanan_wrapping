<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormPesanan extends Model
{
    protected $table = 'form_pesanans';
    protected $primaryKey = 'id_form';

    protected $fillable = [
        'id_pesanan', 'nama_pemesan', 'alamat_pengiriman',
        'no_hp', 'keterangan_tambahan', 'status_verifikasi',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
