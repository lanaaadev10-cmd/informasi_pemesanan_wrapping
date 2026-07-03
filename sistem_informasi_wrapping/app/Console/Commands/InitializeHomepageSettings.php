<?php

namespace App\Console\Commands;

use App\Settings\HomepageSettings;
use Illuminate\Console\Command;

class InitializeHomepageSettings extends Command
{
    protected $signature = 'settings:initialize-homepage';
    protected $description = 'Initialize missing homepage settings properties';

    public function handle(): void
    {
        $settings = app(HomepageSettings::class);
        $reflection = new \ReflectionClass($settings);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $key = $property->getName();
            if (empty($settings->{$key})) {
                $settings->{$key} = '';
            }
        }

        $settings->save();
        $this->info('Homepage settings initialized successfully.');
    }
}
