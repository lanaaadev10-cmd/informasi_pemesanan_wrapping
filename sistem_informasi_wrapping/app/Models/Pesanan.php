<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * ============================================
 *  MODEL PESANAN — Inti dari sistem pemesanan
 * ============================================
 * Model ini mewakili tabel 'pesanans' yang menjadi pusat
 * seluruh alur transaksi: dari pemesanan awal hingga selesai.
 *
 * Alur status (lifecycle):
 * menunggu_konfirmasi_admin → menunggu_pembayaran
 *    ↓                         ↓
 *    → ditolak                → menunggu_verifikasi_pembayaran
 *                                ↓
 *                              dikonfirmasi → sedang_diproses → selesai
 */

class Pesanan extends Model
{
    /*
     * Nama tabel & primary key custom
     * (bukan default 'pesanans' / 'id')
     */
    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';

    /*
     * Relasi yang DI-LOAD OTOMATIS setiap kali query Pesanan dipanggil.
     * Berguna agar data detail & pembayaran selalu tersedia tanpa
     * perlu ->load() manual. Hati-hati dengan performance jika ada ribuan record.
     */
    protected $with = ['details', 'pembayaran'];

    // =============================================
    //  KONSTANTA STATUS — Alur Logika Pemesanan
    // =============================================
    // Setiap konstanta merepresentasikan tahapan dalam siklus hidup pesanan.
    // Menggunakan konstanta (bukan string langsung) agar konsisten
    // dan mudah dilacak jika terjadi perubahan nama status.

    /** Pesanan baru masuk, menunggu admin untuk dicek */
    const STATUS_MENUNGGU_KONFIRMASI_ADMIN = 'menunggu_konfirmasi_admin';
    /** Admin sudah setuju, menunggu pelanggan membayar */
    const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    /** Pelanggan sudah upload bukti, menunggu admin verifikasi */
    const STATUS_MENUNGGU_VERIFIKASI_PEMBAYARAN = 'menunggu_verifikasi_pembayaran';
    /** Pembayaran valid, pesanan dikonfirmasi */
    const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    /** Pengerjaan wrapping sedang berjalan */
    const STATUS_SEDANG_DIPROSES = 'sedang_diproses';
    /** Pengerjaan selesai */
    const STATUS_SELESAI = 'selesai';
    /** Pesanan ditolak oleh admin (alasan di catatan_admin) */
    const STATUS_DITOLAK = 'ditolak';

    // =============================================
    //  SUMBER PESANAN (order_source)
    // =============================================
    /** Pesanan dari website (customer online) */
    const ORDER_SOURCE_ONLINE = 'online';
    /** Pesanan dibuat manual oleh admin (pelanggan datang langsung) */
    const ORDER_SOURCE_OFFLINE = 'offline';

    /*
     * Kolom yang boleh diisi secara massal (mass assignment).
     * 'customer_name', 'whatsapp_number', 'address' khusus untuk
     * pesanan offline yang tidak memiliki relasi ke user.
     */
    protected $fillable = [
        'id_user', 'kode_pesanan', 'tanggal_pesan',
        'status', 'catatan_admin', 'total_harga',
        'order_source', 'customer_name', 'whatsapp_number', 'address',
    ];

    /*
     * Scope untuk memfilter sumber pesanan.
     * Contoh: Pesanan::online()->get() → hanya pesanan dari website.
     */
    public function scopeOnline($query)
    {
        return $query->where('order_source', self::ORDER_SOURCE_ONLINE);
    }

    public function scopeOffline($query)
    {
        return $query->where('order_source', self::ORDER_SOURCE_OFFLINE);
    }

    /*
     * Casting tipe data agar otomatis dikonversi saat diakses.
     * 'tanggal_pesan' di-cast ke Carbon (datetime) agar mudah
     * diformat dan dimanipulasi tanggalnya.
     */
    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // =============================================
    //  RELASI ANTAR MODEL
    // =============================================

    /*
     * Relasi ke User (pelanggan yang membuat pesanan).
     * Untuk pesanan offline (id_user = null), dipakai withDefault
     * agar tidak error saat mengakses $pesanan->user->name.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id')
            ->withDefault(fn($user) => $user ?: new User(['name' => $this->customer_name ?? 'Offline Order']));
    }

    /** Detail item layanan yang dipesan (bisa lebih dari 1 jenis) */
    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    /** Form data kendaraan (model, warna, plat nomor, dll) */
    public function form()
    {
        return $this->hasOne(FormPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    /** Data pembayaran (bukti transfer, metode, status verifikasi) */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    /** Semua notifikasi yang terkait dengan pesanan ini */
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_pesanan', 'id_pesanan');
    }

    // =============================================
    //  ACCESSOR / HELPER
    // =============================================

    /*
     * Accessor untuk menampilkan label status yang human-readable.
     * Dipanggil via $pesanan->label_status (tanpa get...Attribute).
     */
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
            default                                     => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }

    /*
     * Accessor untuk menentukan warna badge status di UI.
     * Digunakan di blade & filament untuk mewarnai badge/label status.
     */
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
            default                                     => 'gray',
        };
    }

    /** Cek apakah user boleh upload bukti bayar (hanya di tahap menunggu_pembayaran) */
    public function bisaUploadBukti(): bool
    {
        return (string) $this->status === self::STATUS_MENUNGGU_PEMBAYARAN;
    }

    /** Cek apakah invoice boleh diunduh (hanya setelah pembayaran dikonfirmasi) */
    public function bisaUnduhInvoice(): bool
    {
        return in_array((string) $this->status, [
            self::STATUS_DIKONFIRMASI,
            self::STATUS_SEDANG_DIPROSES,
            self::STATUS_SELESAI,
        ]);
    }

    // =============================================
    //  CATATAN PENTING
    // =============================================
    // Notifikasi ke user TIDAK dikirim dari model booted() untuk
    // menghindari duplikasi. Semua notifikasi dikirim melalui
    // Event & Listener (app/Events/ & app/Listeners/).
    // Lihat EventServiceProvider.php untuk registrasi event.
    // =============================================
}
