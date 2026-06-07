<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    const TTL_24H = 86400;
    const TTL_1H  = 3600;

    const PREFIX_SETTINGS = 'settings_';
    const PREFIX_MODEL    = 'model_';

    public static function remember(string $key, callable $callback, int $ttl = self::TTL_24H): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    public static function clear(string $key): void
    {
        Cache::forget($key);
    }

    public static function clearGroup(string $group): void
    {
        Cache::forget(self::PREFIX_SETTINGS . $group);
    }

    public static function clearAllSettings(): void
    {
        $groups = [
            'company', 'homepage', 'layanan', 'galeri', 'tentang_kami',
            'katalog', 'pesanan', 'keranjang_checkout', 'dashboard_customer',
            'profil_page', 'content', 'layout',
        ];
        foreach ($groups as $group) {
            self::clearGroup($group);
        }
    }

    public static function getSettings(string $group, string $class): mixed
    {
        return self::remember(self::PREFIX_SETTINGS . $group, function () use ($class) {
            return app($class);
        }, self::TTL_24H);
    }

    public static function clearModel(string $key): void
    {
        Cache::forget(self::PREFIX_MODEL . $key);
    }
}
