<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model Pesanan (Transaksi Pemesanan Jasa Wrapping)
 *
 * @property int $id_pesanan Primary key pesanan
 * @property int|null $id_user ID customer pemilik pesanan (null untuk guest / walk-in manual)
 * @property string $kode_pesanan Kode unik pesanan (misal: ORD-20260929-XXXX)
 * @property \Carbon\Carbon $tanggal_pesan Waktu pesanan dibuat
 * @property \Carbon\Carbon|null $booking_date Tanggal pengerjaan terjadwal
 * @property string $status Status tahapan pesanan (mengacu pada OrderStatus enum)
 * @property string|null $catatan_admin Catatan khusus dari admin
 * @property float $total_harga Total nominal biaya pesanan
 * @property string|null $whatsapp_number Nomor WhatsApp customer
 * @property string $order_source Asal pesanan: 'online' atau 'offline'
 * @property string|null $customer_name Nama customer untuk pesanan offline/walk-in
 * @property string|null $address Alamat customer
 * @property int|null $created_by_admin_id ID admin pembuat pesanan offline
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User|null $createdByAdmin Admin pembuat pesanan offline
 * @property-read User|null $user Customer pemilik pesanan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DetailPesanan> $details Rincian paket layanan
 * @property-read FormPesanan|null $form Formulir data kendaraan & lokasi
 * @property-read Pembayaran|null $pembayaran Data transaksi pembayaran
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notifikasi> $notifikasis Riwayat notifikasi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Rating> $ratings Ulasan customer
 */
class Pesanan extends Model
{
    protected $table = 'pesanans';

    protected $primaryKey = 'id_pesanan';

    // =============================================
    //  STATUS CONSTANTS — Alur Logika Pemesanan
    //  (Diselaraskan dengan App\Enums\OrderStatus)
    // =============================================
    const STATUS_MENUNGGU_KONFIRMASI_ADMIN = 'menunggu_konfirmasi_admin';

    const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';

    const STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN = 'menunggu_verifikasi_pembayaran';

    const STATUS_DIKONFIRMASI = 'dikonfirmasi';

    const STATUS_SEDANG_DIPROSES = 'sedang_diproses';

    const STATUS_SELESAI = 'selesai';

    const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'id_user', 'kode_pesanan', 'tanggal_pesan', 'booking_date',
        'status', 'catatan_admin', 'total_harga',
        'whatsapp_number', 'order_source', 'customer_name',
        'address', 'created_by_admin_id',
    ];

    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'booking_date' => 'date',
        'total_harga' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Konversi status pesanan ke PHP Backed Enum OrderStatus (jika valid)
     */
    public function getStatusEnumAttribute(): ?OrderStatus
    {
        return OrderStatus::tryFrom((string) $this->status);
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id', 'id');
    }

    // =============================================
    //  RELASI
    // =============================================
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function form(): HasOne
    {
        return $this->hasOne(FormPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_pesanan', 'id_pesanan');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'id_pesanan', 'id_pesanan');
    }

    // =============================================
    //  HELPER - Label Status untuk tampilan
    // =============================================
    public function getLabelStatusAttribute(): string
    {
        return match ((string) $this->status) {
            self::STATUS_MENUNGGU_KONFIRMASI_ADMIN => 'Menunggu Konfirmasi Admin',
            self::STATUS_MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran',
            self::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN => 'Menunggu Verifikasi Pembayaran',
            self::STATUS_DIKONFIRMASI => 'Dikonfirmasi',
            self::STATUS_SEDANG_DIPROSES => 'Sedang Diproses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITOLAK => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }

    public function getWarnaBadgeAttribute(): string
    {
        return match ((string) $this->status) {
            self::STATUS_MENUNGGU_KONFIRMASI_ADMIN => 'yellow',
            self::STATUS_MENUNGGU_PEMBAYARAN => 'blue',
            self::STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN => 'orange',
            self::STATUS_DIKONFIRMASI => 'green',
            self::STATUS_SEDANG_DIPROSES => 'purple',
            self::STATUS_SELESAI => 'emerald',
            self::STATUS_DITOLAK => 'red',
            default => 'gray',
        };
    }

    /**
     * Apakah user boleh upload bukti pembayaran?
     */
    public function bisaUploadBukti(): bool
    {
        return (string) $this->status === self::STATUS_MENUNGGU_PEMBAYARAN;
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
    //  NOTES - Notifikasi di-handle oleh Event & Listener
    //  Lihat: app/Events/ & app/Listeners/ & EventServiceProvider.php
    //  Alasan: Mencegah duplikasi notifikasi dari model booted + service events
    // =============================================
}
