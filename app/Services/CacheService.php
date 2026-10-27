<?php

namespace App\Services;

use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Reservasi;
use App\Settings\CompanySettings;
use App\Settings\ContentSettings;
use App\Settings\LayananSettings;
use App\Settings\LayoutSettings;
use Illuminate\Support\Facades\Cache;

/**
 * CacheService — Layanan Terpusat Manajemen Cache Aplikasi
 * 
 * Bertanggung jawab atas persistensi data read-heavy, penetapan TTL proporsional,
 * dan penyediaan mekanisme invalidasi presisi tanpa menggunakan Cache::flush() sembarangan.
 */
class CacheService
{
    public const TTL_ONE_DAY = 86400;     // 24 jam (Settings, Layanan, Galeri)
    public const TTL_SHORT = 600;         // 10 menit (Statistik landing, counter dinamis)

    // Cache keys constants (Namespaced & Explicit)
    public const SITE_PROFIL_SETTINGS = 'site_profil_settings';
    public const COMPANY_SETTINGS     = 'company_settings';
    public const LAYANAN_SETTINGS     = 'layanan_settings';
    public const LAYOUT_SETTINGS      = 'layout_settings';
    public const CONTENT_SETTINGS     = 'content_settings';
    public const TESTIMONI_RATINGS    = 'testimoni_ratings';

    public const SITE_LAYANANS        = 'site_layanans';
    public const KATALOG_LAYANANS     = 'katalog_layanans';
    public const DASHBOARD_LAYANANS   = 'dashboard_layanans';

    public const LANDING_GALERIS      = 'landing_galeris';
    public const DASHBOARD_GALERIS    = 'dashboard_galeris';
    public const PUBLIC_GALERIS       = 'public_galeris';

    public const LANDING_STATS        = 'landing_stats';

    /**
     * Get cached setting atau fetch dari database dengan custom TTL
     */
    public static function remember(string $key, callable $callback, int $ttl = self::TTL_ONE_DAY)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Clear cache spesifik berdasarkan kunci
     */
    public static function clear(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * Bersihkan semua cache layanan frontend & katalog
     */
    public static function clearLayananCaches(): void
    {
        Cache::forget(self::SITE_LAYANANS);
        Cache::forget(self::KATALOG_LAYANANS);
        Cache::forget(self::DASHBOARD_LAYANANS);
        Cache::forget(self::LANDING_STATS);
    }

    /**
     * Bersihkan semua cache galeri
     */
    public static function clearGaleriCaches(): void
    {
        Cache::forget(self::LANDING_GALERIS);
        Cache::forget(self::DASHBOARD_GALERIS);
        Cache::forget(self::PUBLIC_GALERIS);
        Cache::forget('public_galeris_mobil');
        Cache::forget('public_galeris_motor');
        Cache::forget('public_galeris_kaca_film');
        Cache::forget('public_galeris_branding');
    }

    /**
     * Bersihkan cache statistik landing page
     */
    public static function clearStatsCaches(): void
    {
        Cache::forget(self::LANDING_STATS);
    }

    /**
     * Bersihkan cache settings profil perusahaan & tampilan
     */
    public static function clearSettingsCaches(): void
    {
        Cache::forget(self::SITE_PROFIL_SETTINGS);
        Cache::forget(self::COMPANY_SETTINGS);
        Cache::forget(self::LAYANAN_SETTINGS);
        Cache::forget(self::LAYOUT_SETTINGS);
        Cache::forget(self::CONTENT_SETTINGS);
    }

    /**
     * Bersihkan semua cache publik terkontrol
     */
    public static function clearAll(): void
    {
        self::clearSettingsCaches();
        self::clearLayananCaches();
        self::clearGaleriCaches();
        Cache::forget(self::TESTIMONI_RATINGS);
    }

    /**
     * Dapatkan daftar layanan aktif untuk landing page (Beranda & Seksi Layanan)
     */
    public static function getSiteLayanans()
    {
        return Cache::remember(self::SITE_LAYANANS, self::TTL_ONE_DAY, function () {
            return Layanan::where('is_active', true)
                ->orderBy('id_layanan')
                ->get();
        });
    }

    /**
     * Dapatkan daftar layanan aktif untuk halaman katalog
     */
    public static function getKatalogLayanans()
    {
        return Cache::remember(self::KATALOG_LAYANANS, self::TTL_ONE_DAY, function () {
            return Layanan::where('is_active', true)
                ->orderBy('id_layanan')
                ->get();
        });
    }

    /**
     * Dapatkan daftar layanan aktif untuk customer dashboard
     */
    public static function getDashboardLayanans()
    {
        return Cache::remember(self::DASHBOARD_LAYANANS, self::TTL_ONE_DAY, function () {
            return Layanan::where('is_active', true)
                ->orderBy('id_layanan')
                ->get();
        });
    }

    /**
     * Dapatkan foto galeri untuk landing page (6 item)
     */
    public static function getLandingGaleris(int $take = 6)
    {
        return Cache::remember(self::LANDING_GALERIS . "_{$take}", self::TTL_ONE_DAY, function () use ($take) {
            return Galeri::latest()
                ->take($take)
                ->get();
        });
    }

    /**
     * Dapatkan foto galeri untuk customer dashboard (6 item)
     */
    public static function getDashboardGaleris(int $take = 6)
    {
        return Cache::remember(self::DASHBOARD_GALERIS . "_{$take}", self::TTL_ONE_DAY, function () use ($take) {
            return Galeri::latest()
                ->take($take)
                ->get();
        });
    }

    /**
     * Dapatkan seluruh karya galeri untuk halaman /galeri-karya (dengan filter kategori opsional)
     */
    public static function getPublicGaleris(?string $kategori = null)
    {
        $key = $kategori ? self::PUBLIC_GALERIS . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $kategori) : self::PUBLIC_GALERIS;
        
        return Cache::remember($key, self::TTL_ONE_DAY, function () use ($kategori) {
            $query = Galeri::query();
            if ($kategori) {
                $query->where('kategori', $kategori);
            }
            return $query->latest()->get();
        });
    }

    /**
     * Dapatkan ringkasan statistik landing page (minHarga & totalKlien)
     */
    public static function getLandingStats(): array
    {
        return Cache::remember(self::LANDING_STATS, self::TTL_SHORT, function () {
            $minHarga = Layanan::where('is_active', true)
                ->where('harga', '>', 0)
                ->min('harga') ?? 450000;

            $completedBookings = Reservasi::whereIn('status', ['completed', 'approved'])->count();
            $completedPesanans = Pesanan::whereIn('status', ['selesai', 'diproses'])->count();
            $totalKlien = 500 + $completedBookings + $completedPesanans;

            return [
                'minHarga'   => $minHarga,
                'totalKlien' => $totalKlien,
            ];
        });
    }

    /**
     * Get settings helpers
     */
    public static function getLayananSettings()
    {
        return self::remember(self::LAYANAN_SETTINGS, fn() => app(LayananSettings::class));
    }

    public static function getCompanySettings()
    {
        return self::remember(self::COMPANY_SETTINGS, fn() => app(CompanySettings::class));
    }

    public static function getLayoutSettings()
    {
        return self::remember(self::LAYOUT_SETTINGS, fn() => app(LayoutSettings::class));
    }

    public static function getContentSettings()
    {
        return self::remember(self::CONTENT_SETTINGS, fn() => app(ContentSettings::class));
    }
}
