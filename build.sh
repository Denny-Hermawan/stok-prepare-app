#!/usr/bin/env bash

# Exit on error
set -e

echo "Starting build process..."

# Install PHP dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Install Node dependencies and build assets
echo "Installing NPM dependencies..."
npm ci

echo "Building assets..."
npm run build

# Clear and cache config
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "Build process completed!"
