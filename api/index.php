<?php

// Simple debug first
if (isset($_GET['debug']) || strpos($_SERVER['REQUEST_URI'], '/debug') !== false) {
    echo "<h1>Debug Info</h1>";
    echo "<p>PHP Version: " . PHP_VERSION . "</p>";
    echo "<p>Current Directory: " . getcwd() . "</p>";
    echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";
    echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";

    echo "<h2>Environment Variables</h2>";
    echo "<p>APP_KEY: " . (getenv('APP_KEY') ? 'SET (' . substr(getenv('APP_KEY'), 0, 20) . '...)' : 'NOT SET') . "</p>";
    echo "<p>APP_ENV: " . getenv('APP_ENV') . "</p>";
    echo "<p>APP_DEBUG: " . getenv('APP_DEBUG') . "</p>";
    echo "<p>DB_HOST: " . (getenv('DB_HOST') ? 'SET' : 'NOT SET') . "</p>";

    echo "<h2>Files Check</h2>";
    echo "<p>Autoload exists: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'YES' : 'NO') . "</p>";
    echo "<p>Bootstrap exists: " . (file_exists(__DIR__ . '/../bootstrap/app.php') ? 'YES' : 'NO') . "</p>";
    echo "<p>Public/index exists: " . (file_exists(__DIR__ . '/../public/index.php') ? 'YES' : 'NO') . "</p>";

    if (file_exists(__DIR__ . '/../')) {
        echo "<h2>Root Directory Contents</h2>";
        echo "<pre>";
        print_r(scandir(__DIR__ . '/../'));
        echo "</pre>";
    }

    exit;
}

// Try to load Laravel
try {
    echo "<!-- Loading Laravel -->\n";

    // Define Laravel start time
    define('LARAVEL_START', microtime(true));

    // Check and load autoloader
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        throw new Exception('Composer autoload not found at: ' . __DIR__ . '/../vendor/autoload.php');
    }

    require __DIR__ . '/../vendor/autoload.php';
    echo "<!-- Autoload loaded -->\n";

    // Check and load Laravel app
    if (!file_exists(__DIR__ . '/../bootstrap/app.php')) {
        throw new Exception('Laravel bootstrap not found at: ' . __DIR__ . '/../bootstrap/app.php');
    }

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "<!-- App bootstrapped -->\n";

    // Create kernel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "<!-- Kernel created -->\n";

    // Handle request
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    echo "<!-- Request handled -->\n";

    $response->send();

    $kernel->terminate($request, $response);

} catch (Exception $e) {
    echo "<h1>Laravel Bootstrap Error</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<h2>Stack Trace:</h2>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";

    echo "<h2>Debug Info</h2>";
    echo "<p>Current directory: " . getcwd() . "</p>";
    echo "<p>Script path: " . __FILE__ . "</p>";
    echo "<p>Looking for autoload at: " . __DIR__ . '/../vendor/autoload.php' . "</p>";
    echo "<p>Looking for bootstrap at: " . __DIR__ . '/../bootstrap/app.php' . "</p>";

    if (is_dir(__DIR__ . '/../')) {
        echo "<h3>Root directory contents:</h3><pre>";
        print_r(scandir(__DIR__ . '/../'));
        echo "</pre>";
    }

    http_response_code(500);
}
