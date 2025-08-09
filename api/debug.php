<?php
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Current Directory: " . getcwd() . "\n";
echo "Files in current directory:\n";
print_r(scandir('.'));

if (file_exists('../vendor/autoload.php')) {
    echo "Autoload exists\n";
    require '../vendor/autoload.php';
} else {
    echo "Autoload not found\n";
    exit;
}

if (file_exists('../bootstrap/app.php')) {
    echo "Bootstrap exists\n";
    $app = require_once '../bootstrap/app.php';
    echo "App created: " . get_class($app) . "\n";
} else {
    echo "Bootstrap not found\n";
}

echo "Environment variables:\n";
echo "APP_KEY: " . (getenv('APP_KEY') ? 'SET' : 'NOT SET') . "\n";
echo "APP_ENV: " . getenv('APP_ENV') . "\n";
