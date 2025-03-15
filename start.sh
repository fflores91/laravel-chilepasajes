#!/usr/bin/env bash
set -e

echo "Running composer install..."
composer install --no-dev --working-dir=/var/www/html

echo "Caching configuration..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Starting server..."
exec /usr/local/bin/start-nginx-php-fpm
