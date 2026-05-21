<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';

    // =============================================
    //  STATUS CONSTANTS — Alur Logika Pemesanan
    // =============================================
    const STATUS_MENUNGGU_KONFIRMASI_ADMIN     = 'menunggu_konfirmasi_admin';
    const STATUS_MENUNGGU_PEMBAYARAN           = 'menunggu_pembayaran';
    const STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN = 'menunggu_verifikasi_pembayaran';
    const STATUS_DIKONFIRMASI                  = 'dikonfirmasi';
    const STATUS_SEDANG_DIPROSES               = 'sedang_diproses';
    const STATUS_SELESAI                       = 'selesai';
    const STATUS_DITOLAK                       = 'ditolak';

    protected $fillable = [
        'id_user', 'kode_pesanan', 'tanggal_pesan',
        'status', 'catatan_admin', 'total_harga',
    ];

    protected $casts = [
        // 'status' cast intentionally removed — kept as plain string
        // for compatibility with all string comparisons across controllers and views
        'tanggal_pesan' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =============================================
    //  RELASI
    // =============================================
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

    // =============================================
    //  HELPER - Label Status untuk tampilan
    // =============================================
    public function getLabelStatusAttribute(): string
    {
        return match ((string) $this->status) {
            self::STATUS_MENUNGGU_KONFIRMASI_ADMIN      => 'Menunggu Konfirmasi Admin',
            self::STATUS_MENUNGGU_PEMBAYARAN            => 'Menunggu Pembayaran',
            self::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN => 'Menunggu Verifikasi Pembayaran',
            self::STATUS_DIKONFIRMASI                   => 'Dikonfirmasi',
            self::STATUS_SEDANG_DIPROSES                => 'Sedang Diproses',
            self::STATUS_SELESAI                        => 'Selesai',
            self::STATUS_DITOLAK                        => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }

    public function getWarnaBadgeAttribute(): string
    {
        return match ((string) $this->status) {
            self::STATUS_MENUNGGU_KONFIRMASI_ADMIN      => 'yellow',
            self::STATUS_MENUNGGU_PEMBAYARAN            => 'blue',
            self::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN => 'orange',
            self::STATUS_DIKONFIRMASI                   => 'green',
            self::STATUS_SEDANG_DIPROSES                => 'purple',
            self::STATUS_SELESAI                        => 'emerald',
            self::STATUS_DITOLAK                        => 'red',
            default => 'gray',
        };
    }

    /**
     * Apakah user boleh upload bukti pembayaran?
     */
    public function bisaUploadBukti(): bool
    {
        return $this->status === self::STATUS_MENUNGGU_PEMBAYARAN;
    }

    /**
     * Apakah invoice bisa diunduh?
     */
    public function bisaUnduhInvoice(): bool
    {
        return in_array((string) $this->status, [
            self::STATUS_DIKONFIRMASI,
            self::STATUS_SEDANG_DIPROSES,
            self::STATUS_SELESAI,
        ]);
    }

    // =============================================
    //  BOOT - Otomatisasi Notifikasi
    // =============================================
    protected static function booted()
    {
        static::created(function ($pesanan) {
            // Notifikasi ke user: pesanan berhasil dibuat
            Notifikasi::create([
                'id_user'    => $pesanan->id_user,
                'id_pesanan' => $pesanan->id_pesanan,
                'judul'      => '✅ Pesanan Berhasil Dibuat',
                'pesan'      => 'Pesanan #' . $pesanan->kode_pesanan . ' telah diterima. Tunggu konfirmasi dari admin kami.',
                'tipe'       => 'pesanan',
                'is_read'    => false,
            ]);
        });

        static::updated(function ($pesanan) {
            if (!$pesanan->wasChanged('status')) return;

            $notifikasiUser = match ((string) $pesanan->status) {
                self::STATUS_MENUNGGU_PEMBAYARAN => [
                    'judul' => '🔔 Pesanan Dikonfirmasi — Silakan Bayar',
                    'pesan' => 'Pesanan #' . $pesanan->kode_pesanan . ' telah dikonfirmasi admin. Silakan lakukan pembayaran untuk memulai pengerjaan.',
                ],
                self::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN => null,
                self::STATUS_DIKONFIRMASI => [
                    'judul' => '💳 Pembayaran Terverifikasi — Invoice Tersedia',
                    'pesan' => 'Pembayaran Anda untuk pesanan #' . $pesanan->kode_pesanan . ' telah diverifikasi. Anda sekarang dapat mengunduh invoice.',
                ],
                self::STATUS_SEDANG_DIPROSES => [
                    'judul' => '🔧 Pesanan Sedang Diproses',
                    'pesan' => 'Kendaraan Anda untuk pesanan #' . $pesanan->kode_pesanan . ' sedang dalam tahap pengerjaan oleh tim kami.',
                ],
                self::STATUS_SELESAI => [
                    'judul' => '🎉 Pesanan Selesai!',
                    'pesan' => 'Halo! Pengerjaan kendaraan Anda untuk pesanan #' . $pesanan->kode_pesanan . ' telah selesai. Silakan ambil di toko kami. Terima kasih!',
                ],
                self::STATUS_DITOLAK => [
                    'judul' => '❌ Pesanan Ditolak',
                    'pesan' => 'Maaf, pesanan #' . $pesanan->kode_pesanan . ' tidak dapat diproses. Silakan hubungi admin untuk informasi lebih lanjut.',
                ],
                default => null,
            };

            if ($notifikasiUser) {
                Notifikasi::create([
                    'id_user'    => $pesanan->id_user,
                    'id_pesanan' => $pesanan->id_pesanan,
                    'judul'      => $notifikasiUser['judul'],
                    'pesan'      => $notifikasiUser['pesan'],
                    'tipe'       => 'info',
                    'is_read'    => false,
                ]);
            }
        });
    }
}
