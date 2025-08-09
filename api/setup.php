<?php
require __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\Artisan;

// Jalankan commands yang diperlukan setelah deployment
try {
    // Config cache
    Artisan::call('config:cache');
    echo "Config cached successfully\n";

    // Route cache
    Artisan::call('route:cache');
    echo "Routes cached successfully\n";

    // View cache
    Artisan::call('view:cache');
    echo "Views cached successfully\n";

    // Run migrations
    Artisan::call('migrate', ['--force' => true]);
    echo "Migrations completed successfully\n";

    echo "Setup completed successfully!";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    http_response_code(500);
}
