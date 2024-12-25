#!/bin/bash
set -e

cd /var/www/html/bagisto

# Install dependencies
composer install

# Create required directories
echo "Creating required directories..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# Set proper permissions
echo "Setting proper permissions..."
chown -R bagisto:www-data storage
chown -R bagisto:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create storage link
echo "Creating storage link..."
php artisan storage:link

# Run migrations and seeds
echo "Running migrations and seeds..."
php artisan migrate --force
php artisan db:seed --force

# Create installation marker
echo "Creating installation marker..."
touch storage/installed
chmod 775 storage/installed
chown bagisto:www-data storage/installed

# Publish vendor assets
echo "Publishing vendor assets..."
php artisan vendor:publish --all --force
php artisan bagisto:publish --force

# Clear all caches first
echo "Clearing cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize and cache everything
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize

# Start Apache in foreground
echo "Starting Apache..."
exec apache2-foreground