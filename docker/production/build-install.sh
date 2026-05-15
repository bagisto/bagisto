#!/bin/bash
# ==========================================================================
# build-install.sh — Runs during docker build to fully install Bagisto.
#
# Starts MySQL temporarily, runs migrations + seeding, then shuts MySQL
# down cleanly. The populated /var/lib/mysql is baked into the image layer.
# ==========================================================================
set -e

echo "[build-install] Initializing MySQL data directory..."
mkdir -p /run/mysqld
rm -rf /var/lib/mysql
mkdir -p /var/lib/mysql
chown -R mysql:mysql /run/mysqld /var/lib/mysql
mysqld --initialize-insecure --user=mysql --datadir=/var/lib/mysql

echo "[build-install] Starting MySQL..."
mysqld --user=mysql --datadir=/var/lib/mysql &
MYSQL_PID=$!

# Wait for MySQL to accept connections
echo "[build-install] Waiting for MySQL to be ready..."
for i in $(seq 1 60); do
    if mysqladmin ping -h 127.0.0.1 --silent 2>/dev/null; then
        echo "[build-install] MySQL is ready."
        break
    fi
    if [ "$i" -eq 60 ]; then
        echo "[build-install] ERROR: MySQL did not start within 60 seconds."
        exit 1
    fi
    sleep 1
done

# Create database and user
echo "[build-install] Creating database and user..."
mysql -h 127.0.0.1 -u root < /docker-entrypoint-initdb.d/init.sql

# Install Bagisto
cd /var/www/bagisto

echo "[build-install] Generating application key..."
php artisan key:generate --force --no-interaction

echo "[build-install] Running Bagisto installation..."
php artisan bagisto:install --skip-env-check --skip-admin-creation --skip-github-star --no-interaction

echo "[build-install] Running database seeders..."
php artisan db:seed --class="Webkul\\Installer\\Database\\Seeders\\ProductTableSeeder"

echo "[build-install] Running indexers..."
php artisan index:index --mode=full

# Shut down MySQL cleanly
echo "[build-install] Shutting down MySQL..."
mysqladmin -h 127.0.0.1 -u root shutdown
wait $MYSQL_PID

# Fix ownership after shutdown
chown -R mysql:mysql /var/lib/mysql

echo "[build-install] Bagisto installation complete."
