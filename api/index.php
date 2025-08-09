<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting Laravel bootstrap...\n";

// Check if we're in the right directory
echo "Current directory: " . getcwd() . "\n";
echo "Looking for autoload at: " . __DIR__ . '/../vendor/autoload.php' . "\n";

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "Autoload found, requiring...\n";
    require __DIR__ . '/../vendor/autoload.php';
} else {
    echo "ERROR: Autoload not found!\n";
    echo "Directory contents:\n";
    print_r(scandir(__DIR__ . '/../'));
    exit(1);
}

echo "Autoload successful, looking for bootstrap...\n";

if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
    echo "Bootstrap found, requiring...\n";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "App created successfully\n";
} else {
    echo "ERROR: Bootstrap not found!\n";
    exit(1);
}

try {
    echo "Making kernel...\n";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel created successfully\n";

    echo "Capturing request...\n";
    $request = Illuminate\Http\Request::capture();
    echo "Request captured: " . $request->getMethod() . " " . $request->getRequestUri() . "\n";

    echo "Handling request...\n";
    $response = $kernel->handle($request);
    echo "Request handled successfully\n";

    $response->send();
    $kernel->terminate($request, $response);

} catch (Exception $e) {
    echo "ERROR in Laravel bootstrap: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
