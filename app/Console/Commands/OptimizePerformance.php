<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class OptimizePerformance extends Command
{
    protected $signature = 'app:optimize-performance';
    protected $description = 'Optimize application performance (cache compilation, etc)';

    public function handle()
    {
        $this->info('🚀 Starting Performance Optimization...');

        // 1. Clear existing caches
        $this->info('📦 Clearing application caches...');
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('config:clear');

        // 2. Cache configuration
        $this->info('⚙️  Caching configuration...');
        $this->call('config:cache');

        // 3. Cache routes
        $this->info('🛣️  Caching routes...');
        $this->call('route:cache');

        // 4. Compile views
        $this->info('🎨 Compiling views...');
        $this->call('view:cache');

        // 5. Clear all custom caches
        $this->info('🗑️  Clearing custom settings caches...');
        CacheService::clearAll();

        // 6. Optimize autoloader
        $this->info('🔧 Optimizing Composer autoloader...');
        exec('composer dump-autoload --optimize');

        $this->info('✅ Performance Optimization Complete!');
        $this->newLine();
        $this->table(
            ['Component', 'Status'],
            [
                ['Configuration Cache', '✓ Done'],
                ['Route Cache', '✓ Done'],
                ['View Cache', '✓ Done'],
                ['Settings Cache Cleared', '✓ Done'],
                ['Autoloader Optimized', '✓ Done'],
            ]
        );

        $this->newLine();
        $this->info('📊 Performance Tips:');
        $this->line('- Use Redis for cache (faster than file)');
        $this->line('- Add database indexes on frequently queried columns');
        $this->line('- Monitor queries with Laravel Debugbar');
        $this->line('- Keep cache TTL at 24 hours for settings');
    }
}
