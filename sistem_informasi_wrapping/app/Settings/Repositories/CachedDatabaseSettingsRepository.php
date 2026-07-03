<?php

namespace App\Settings\Repositories;

use App\Services\CacheService;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository;

class CachedDatabaseSettingsRepository extends DatabaseSettingsRepository
{
    public function createProperty(string $group, string $name, $payload, bool $locked = false): void
    {
        parent::createProperty($group, $name, $payload, $locked);
        CacheService::clearGroup($group);
    }

    public function updatePropertiesPayload(string $group, array $properties): void
    {
        parent::updatePropertiesPayload($group, $properties);
        CacheService::clearGroup($group);
    }

    public function deleteProperty(string $group, string $name): void
    {
        parent::deleteProperty($group, $name);
        CacheService::clearGroup($group);
    }
}
