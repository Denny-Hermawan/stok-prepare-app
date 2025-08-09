#!/bin/bash
composer install --no-dev --optimize-autoloader --no-interaction
php artisan key:generate --force
php artisan config:cache
