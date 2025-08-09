<?php

use Illuminate\Support\Facades\Artisan;

// Auto-setup untuk deployment pertama
if (!file_exists(__DIR__ . '/../bootstrap/cache/config.php')) {
    try {
        // Load Laravel
        require_once __DIR__ . '/../bootstrap/app.php';

        // Run setup commands
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        // Run migrations jika diperlukan
        if (env('AUTO_MIGRATE', true)) {
            Artisan::call('migrate', ['--force' => true]);
        }
    } catch (Exception $e) {
        // Log error tapi tetap lanjutkan
        error_log("Setup error: " . $e->getMessage());
    }
}

// Load aplikasi Laravel normal
require __DIR__ . '/../public/index.php';
