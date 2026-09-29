<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'notes',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date:Y-m-d',
            'status' => BookingStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
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

    public function payment(): HasOne
    {
        return $this->hasOne(BookingPayment::class);
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

    /**
     * URL WhatsApp untuk kirim konfirmasi / info booking ke pelanggan
     */
    public function getWhatsappNotificationUrlAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->pelanggan_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $layananNama = $this->layanan?->nama_layanan ?? 'Wrapping & Protection';
        $tanggal = $this->booking_date ? $this->booking_date->translatedFormat('d F Y') : '-';
        $jam = $this->booking_time ?: 'Sesuai Jadwal';
        $status = $this->label_status;

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
