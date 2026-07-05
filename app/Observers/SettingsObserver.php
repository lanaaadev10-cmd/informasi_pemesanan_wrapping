<?php

namespace App\Observers;

use App\Services\CacheService;
use Spatie\LaravelSettings\Models\SettingsModel;

class SettingsObserver
{
    /**
     * Clear relevant cache when settings are updated
     */
    public function saving(SettingsModel $model): void
    {
        // Clear cache saat settings akan disave
        $this->clearRelatedCache($model->group);
    }

    /**
     * Clear cache after settings saved
     */
    public function saved(SettingsModel $model): void
    {
        $this->clearRelatedCache($model->group);
    }

    /**
     * Tentukan cache mana yang harus di-clear berdasarkan group
     */
    private function clearRelatedCache(string $group): void
    {
        switch ($group) {
            case 'galeri':
                CacheService::clear(CacheService::GALERI_SETTINGS);
                break;
            case 'layanan':
                CacheService::clear(CacheService::LAYANAN_SETTINGS);
                break;
            case 'company':
                CacheService::clear(CacheService::COMPANY_SETTINGS);
                break;
            case 'layout':
                CacheService::clear(CacheService::LAYOUT_SETTINGS);
                break;
            case 'content':
                CacheService::clear(CacheService::CONTENT_SETTINGS);
                break;
        }
    }
}
