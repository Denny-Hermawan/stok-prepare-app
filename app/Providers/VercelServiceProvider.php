<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class VercelServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Check if running on Vercel
        if ($this->isRunningOnVercel()) {
            $this->configureForVercel();
        }
    }

    public function boot()
    {
        //
    }

    private function isRunningOnVercel(): bool
    {
        return isset($_ENV['VERCEL']) ||
               isset($_ENV['NOW_REGION']) ||
               isset($_SERVER['VERCEL']) ||
               isset($_SERVER['NOW_REGION']);
    }

    private function configureForVercel(): void
    {
        // Set storage path to /tmp
        $this->app->useStoragePath('/tmp');

        // Create necessary directories
        $this->createDirectories([
            '/tmp/framework',
            '/tmp/framework/cache',
            '/tmp/framework/cache/data',
            '/tmp/framework/sessions',
            '/tmp/framework/views',
            '/tmp/logs',
        ]);

        // Override config for Vercel
        config([
            'cache.default' => 'array',
            'cache.stores.file.path' => '/tmp/framework/cache/data',
            'session.driver' => 'array',
            'session.files' => '/tmp/framework/sessions',
            'view.compiled' => '/tmp/framework/views',
            'logging.channels.single.path' => '/tmp/logs/laravel.log',
            'logging.channels.daily.path' => '/tmp/logs/laravel.log',
        ]);
    }

    private function createDirectories(array $paths): void
    {
        foreach ($paths as $path) {
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }
}
