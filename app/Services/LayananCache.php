<?php

namespace App\Services;

/**
 * LayananCache — Layanan Terpusat Manajemen Tembolok / Cache Aplikasi
 * 
 * Standarisasi penamaan Bahasa Indonesia untuk CacheService.
 * Mewarisi CacheService untuk menjaga 100% kompatibilitas sistem.
 */
class LayananCache extends CacheService
{
    /**
     * Menyimpan data ke tembolok atau mengambil data segar jika kedaluwarsa.
     */
    public static function ingat(string $kunci, callable $panggilanBalik, int $ttl = self::TTL_ONE_DAY)
    {
        return static::remember($kunci, $panggilanBalik, $ttl);
    }

    /**
     * Menghapus cache spesifik berdasarkan kunci.
     */
    public static function hapus(string $kunci): void
    {
        static::clear($kunci);
    }

    /**
     * Mengambil galeri untuk halaman depan / landing page.
     */
    public static function ambilGaleriBeranda(int $limit = 6)
    {
        return static::getLandingGaleris($limit);
    }

    /**
     * Mengambil daftar seluruh layanan publik aktif.
     */
    public static function ambilLayananSitus()
    {
        return static::getSiteLayanans();
    }

    /**
     * Mengambil statistik ringkas untuk halaman depan.
     */
    public static function ambilStatistikBeranda(): array
    {
        return static::getLandingStats();
    }

    /**
     * Membersihkan seluruh tembolok situs (layanan, galeri, statistik, settings).
     */
    public static function bersihkanSemuaTembolok(): void
    {
        static::clearAllSiteCache();
    }
}

if (!class_exists('App\Services\LayananCache', false)) {
    class_alias(CacheService::class, 'App\Services\LayananCache');
}
