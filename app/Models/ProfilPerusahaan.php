<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ProfilPerusahaan
 *
 * Model singleton — hanya ada 1 record profil perusahaan di database.
 * Dikelola melalui Admin Panel Filament v5.
 */
class ProfilPerusahaan extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        // Informasi Utama
        'nama_perusahaan',
        'deskripsi',
        'alamat',
        'email',
        'nomor_telepon',
        'logo',
        'maps_url',

        // CMS Dinamis — Tentang Kami
        'visi',
        'misi',
        'sejarah',

        // Sosial Media
        'instagram_url',
        'facebook_url',
        'tiktok_url',
        'whatsapp_url',

        // SEO & Metadata
        'meta_title',
        'meta_description',
    ];

    /**
     * Type casting untuk kolom tertentu.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Helper: Ambil profil perusahaan (singleton pattern).
     * Selalu mengembalikan record pertama, atau null jika belum ada.
     */
    public static function singleton(): ?self
    {
        return static::first();
    }

    /**
     * Accessor: URL lengkap logo untuk ditampilkan di frontend.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return asset('storage/' . $this->logo);
    }

    /**
     * Accessor: Link WhatsApp langsung dari nomor telepon.
     */
    public function getWhatsappLinkAttribute(): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $this->nomor_telepon ?? '');

        // Ganti awalan 0 dengan 62 (kode negara Indonesia)
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        return "https://wa.me/{$nomor}";
    }
}
