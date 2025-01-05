#!/bin/bash

echo "Setting up storage permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Running database migrations and seeds..."
php artisan migrate --force
php artisan db:seed --force
php artisan vendor:publish --all --force
php artisan bagisto:publish --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

apache2-foreground