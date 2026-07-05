<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    const CACHE_DURATION = 60 * 60 * 24; // 24 hours

    // Settings caches
    const LAYANAN_SETTINGS = 'layanan_settings';
    const COMPANY_SETTINGS = 'company_settings';
    const LAYOUT_SETTINGS = 'layout_settings';
    const CONTENT_SETTINGS = 'content_settings';

    /**
     * Get cached setting atau fetch dari database
     */
    public static function remember($key, $callback)
    {
        return Cache::remember($key, self::CACHE_DURATION, $callback);
    }

    /**
     * Clear all application caches
     */
    public static function clearAll()
    {
        Cache::forget(self::LAYANAN_SETTINGS);
        Cache::forget(self::COMPANY_SETTINGS);
        Cache::forget(self::LAYOUT_SETTINGS);
        Cache::forget(self::CONTENT_SETTINGS);
    }

    /**
     * Clear specific cache
     */
    public static function clear($key)
    {
        Cache::forget($key);
    }

    /**
     * Get gallery settings dengan cache
     */
    /**
     * Get layanan settings dengan cache
     */
    public static function getLayananSettings()
    {
        return self::remember(self::LAYANAN_SETTINGS, function () {
            return app(\App\Settings\LayananSettings::class);
        });
    }

    /**
     * Get company settings dengan cache
     */
    public static function getCompanySettings()
    {
        return self::remember(self::COMPANY_SETTINGS, function () {
            return app(\App\Settings\CompanySettings::class);
        });
    }

    /**
     * Get layout settings dengan cache
     */
    public static function getLayoutSettings()
    {
        return self::remember(self::LAYOUT_SETTINGS, function () {
            return app(\App\Settings\LayoutSettings::class);
        });
    }

    /**
     * Get content settings dengan cache
     */
    public static function getContentSettings()
    {
        return self::remember(self::CONTENT_SETTINGS, function () {
            return app(\App\Settings\ContentSettings::class);
        });
    }
}
