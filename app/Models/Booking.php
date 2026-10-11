<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model Booking (Penjadwalan Pengerjaan Wrapping)
 *
 * @property int $id Primary key booking
 * @property string $booking_code Kode referensi booking unik
 * @property int|null $user_id ID akun customer (null jika booking via walk-in)
 * @property string|null $customer_name Nama pemesan
 * @property string|null $customer_phone Nomor telepon / WhatsApp pemesan
 * @property string|null $customer_email Alamat email pemesan
 * @property int $layanan_id Foreign key ke tabel layanans (id_layanan)
 * @property \Carbon\Carbon $booking_date Tanggal pengerjaan yang dijadwalkan
 * @property string|null $booking_time Jam pengerjaan
 * @property string $payment_type Skema pembayaran: 'dp' atau 'lunas'
 * @property string|null $vehicle_name Nama kendaraan (contoh: Honda HR-V 2023)
 * @property string|null $vehicle_color Warna asli kendaraan
 * @property string|null $vehicle_license Plat nomor polisi
 * @property string|null $notes Catatan dari customer
 * @property \App\Enums\BookingStatus $status Status alur booking
 * @property string|null $admin_notes Catatan internal dari admin
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User|null $user
 * @property-read Layanan $layanan
 * @property-read BookingPayment|null $payment
 * @property-read string $label_status
 * @property-read string $warna_badge
 * @property-read string $pelanggan_nama
 * @property-read string $pelanggan_phone
 * @property-read string $pelanggan_email
 * @property-read string $whatsapp_notification_url
 */
class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'booking_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'layanan_id',
        'booking_date',
        'booking_time',
        'payment_type',
        'vehicle_name',
        'vehicle_color',
        'vehicle_license',
        'brand_id',
        'model_id',
        'vehicle_size',
        'source',
        'service_name_snapshot',
        'base_price_snapshot',
        'addons_total_snapshot',
        'grand_total_snapshot',
        'dp_amount_snapshot',
        'remaining_balance_snapshot',
        'material_brand',
        'material_size',
        'material_condition',
        'material_photo',
        'material_disclaimer_agreed',
        'material_status',
        'custom_request_notes',
        'custom_reference_photo',
        'quotation_amount',
        'quotation_notes',
        'quotation_status',
        'notes',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date'               => 'date:Y-m-d',
            'status'                     => BookingStatus::class,
            'base_price_snapshot'        => 'decimal:2',
            'addons_total_snapshot'      => 'decimal:2',
            'grand_total_snapshot'       => 'decimal:2',
            'dp_amount_snapshot'         => 'decimal:2',
            'remaining_balance_snapshot' => 'decimal:2',
            'quotation_amount'           => 'decimal:2',
            'material_disclaimer_agreed' => 'boolean',
            'created_at'                 => 'datetime',
            'updated_at'                 => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'id_layanan');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function addons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BookingAddon::class, 'booking_id');
    }

    public function bookingAddons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BookingAddon::class, 'booking_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(BookingPayment::class);
    }

    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class, 'booking_id', 'id');
    }

    public function getLabelStatusAttribute(): string
    {
        return $this->status->label();
    }

    public function getWarnaBadgeAttribute(): string
    {
        return $this->status->badgeColor();
    }

    public function getPelangganNamaAttribute(): string
    {
        return $this->customer_name ?: ($this->user?->name ?? 'Pelanggan');
    }

    public function getPelangganPhoneAttribute(): string
    {
        return $this->customer_phone ?: ($this->user?->no_hp ?? ($this->user?->phone ?? '-'));
    }

    public function getPelangganEmailAttribute(): string
    {
        return $this->customer_email ?: ($this->user?->email ?? '-');
    }

    public function getJenisLayananAttribute(): string
    {
        if (empty($this->layanan_id) || $this->quotation_status !== 'none' || ($this->quotation_amount !== null && (float) $this->quotation_amount > 0)) {
            return 'custom';
        }
        return $this->layanan?->jenis_layanan ?? 'paket';
    }

    public function getNamaLayananAttribute(): string
    {
        return $this->service_name_snapshot ?: ($this->layanan?->nama_layanan ?? 'Proyek Custom');
    }

    public function getTotalPriceAttribute(): float
    {
        return (float) ($this->grand_total_snapshot ?: ($this->quotation_amount ?: ($this->base_price_snapshot ?? 0)));
    }

    public function getDpAmountAttribute(): float
    {
        return (float) ($this->dp_amount_snapshot ?: round($this->total_price * 0.5));
    }

    public function getRemainingAmountAttribute(): float
    {
        return (float) ($this->remaining_balance_snapshot ?: max(0, $this->total_price - $this->dp_amount));
    }

    /**
     * URL WhatsApp untuk kirim konfirmasi / info booking ke pelanggan
     */
    public function getWhatsappNotificationUrlAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->pelanggan_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $layananNama = $this->service_name_snapshot ?: ($this->layanan?->nama_layanan ?? 'Proyek Custom Wrapping');
        $tanggal = $this->booking_date ? $this->booking_date->translatedFormat('d F Y') : '-';
        $jam = $this->booking_time ?: 'Sesuai Jadwal';
        $status = $this->label_status;

        if ($this->quotation_status === 'sent' && (float) $this->quotation_amount > 0) {
            $totalFmt = number_format((float) $this->quotation_amount, 0, ',', '.');
            $dpFmt = number_format((float) ($this->dp_amount_snapshot ?: round((float) $this->quotation_amount * 0.5)), 0, ',', '.');
            $kendaraan = $this->vehicle_name ?: 'Kendaraan Pelanggan';

            $pesan = "Halo kak *{$this->pelanggan_nama}*,\n\n"
                . "Hasil kesepakatan & penawaran resmi (*Quotation*) proyek Custom Anda di *Dantie Stiker* sudah siap:\n\n"
                . "📌 *Detail Kesepakatan Custom:*\n"
                . "• Kode Booking: *{$this->booking_code}*\n"
                . "• Layanan: {$layananNama}\n"
                . "• Kendaraan: {$kendaraan}\n"
                . "• Tanggal Pengerjaan: {$tanggal}\n"
                . "• Total Biaya Disepakati: *Rp {$totalFmt}*\n"
                . "• Minimal DP (Tanda Jadi): *Rp {$dpFmt}*\n"
                . ($this->quotation_notes ? "• Rincian Pengerjaan: {$this->quotation_notes}\n\n" : "\n")
                . "Silakan lakukan pembayaran DP untuk mengunci antrean pengerjaan di kalender workshop kami. Terima kasih!\n\n"
                . "Salam hangat,\n*Tim Dantie Stiker*";

            return "https://api.whatsapp.com/send?phone={$phone}&text=" . urlencode($pesan);
        }

        $pesan = "Halo kak *{$this->pelanggan_nama}*,\n\n"
            . "Terima kasih telah melakukan pemesanan di *Dantie Stiker*.\n\n"
            . "📌 *Detail Booking:*\n"
            . "• Kode Booking: *{$this->booking_code}*\n"
            . "• Layanan: {$layananNama}\n"
            . "• Tanggal: {$tanggal}\n"
            . "• Jam: {$jam} WIB\n"
            . "• Status: *{$status}*\n\n"
            . "Mohon simpan kode booking ini untuk referensi Anda. Jika ada pertanyaan atau perubahan jadwal, silakan hubungi kami kembali.\n\n"
            . "Salam hangat,\n*Tim Dantie Stiker*";

        return "https://api.whatsapp.com/send?phone={$phone}&text=" . urlencode($pesan);
    }

    public function bisaUploadBukti(): bool
    {
        return $this->status->canUploadPayment();
    }

    public function bisaDibatalkan(): bool
    {
        return $this->status->canBeCancelled();
    }

    protected static function booted(): void
    {
        static::saved(function (Booking $booking) {
            \Cache::forget("slot_quota_{$booking->booking_date->format('Y-m-d')}");
            \Cache::forget("booking_quota_{$booking->booking_date->format('Y-m-d')}");
        });

        static::deleted(function (Booking $booking) {
            \Cache::forget("slot_quota_{$booking->booking_date->format('Y-m-d')}");
            \Cache::forget("booking_quota_{$booking->booking_date->format('Y-m-d')}");
        });
    }
}
