#!/bin/bash
set -e

cd /var/www/html

echo "Creating required directories and files..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
touch storage/logs/laravel.log

echo "Setting proper permissions..."
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "Running database migrations and seeds..."
php artisan migrate --force
php artisan db:seed --force

echo "Publishing vendor assets..."
php artisan vendor:publish --all --force
php artisan bagisto:publish --force

echo "Creating installation marker..."
touch storage/installed
chmod 775 storage/installed
chown www-data:www-data storage/installed

echo "Clearing all caches..."
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache..."
apache2-foreground