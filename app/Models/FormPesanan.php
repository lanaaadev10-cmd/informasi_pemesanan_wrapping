<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model FormPesanan (Data Kendaraan & Pengerjaan)
 *
 * @property int $id_form Primary key form pesanan
 * @property int $id_pesanan Foreign key ke tabel pesanans
 * @property string $nama_pemesan Nama lengkap pemesan
 * @property string $alamat_pengiriman Alamat pemesan
 * @property string $no_hp Nomor HP/WhatsApp
 * @property string|null $keterangan_tambahan Catatan khusus
 * @property string $status_verifikasi Status verifikasi: pending, terverifikasi, perlu_diperbaiki
 * @property string|null $model_kendaraan Jenis/model kendaraan (contoh: Honda Civic Turbo)
 * @property string|null $warna_kendaraan Warna kendaraan saat ini
 * @property string|null $nomor_polisi Plat nomor kendaraan
 * @property string|null $tahun_produksi Tahun perakitan kendaraan
 * @property string|null $lokasi_pengerjaan Lokasi pengerjaan ('toko')
 * @property \Carbon\Carbon|null $jadwal_pengerjaan Waktu mulai pengerjaan
 * @property string|null $estimasi_durasi Estimasi waktu pengerjaan
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Pesanan $pesanan
 */
class FormPesanan extends Model
{
    protected $table = 'form_pesanans';
    protected $primaryKey = 'id_form';

    protected $fillable = [
        'id_pesanan', 'nama_pemesan', 'alamat_pengiriman',
        'no_hp', 'keterangan_tambahan', 'status_verifikasi',
        // Kolom Kendaraan & Jadwal Sesi
        'model_kendaraan', 'warna_kendaraan', 'nomor_polisi', 'tahun_produksi',
        'lokasi_pengerjaan', 'jadwal_pengerjaan', 'estimasi_durasi',
    ];

    protected $casts = [
        'jadwal_pengerjaan' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
