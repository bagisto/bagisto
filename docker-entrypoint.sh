#!/bin/bash
set -e

cd /var/www/html/bagisto

# Install dependencies
composer install

# Verify or generate app key
if ! grep -q "APP_KEY" .env; then
    echo "Generating app key..."
    php artisan key:generate
fi

# Run migrations and seeds
echo "Running migrations and seeds..."
php artisan migrate --force
php artisan db:seed --force



# Set proper permissions
echo "Setting permissions..."
chown -R bagisto:www-data storage
chown -R bagisto:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Optimize application
echo "Optimizing application..."
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan optimize

# Start Apache in foreground
echo "Starting Apache..."
exec apache2-foreground