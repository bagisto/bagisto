#!/bin/bash
# ==========================================================================
# build-install.sh — Fully installs Bagisto while the image is being built.
#
# Brings the bundled database up, runs migrations and seeding against it,
# then shuts it down cleanly so the populated data directory is baked into
# the image layer. The container that results boots straight into a working
# store with no first-run setup.
#
# The database itself is handled by an engine driver — see shared/db/ — so
# this script is the same whether the image bundles MySQL or MariaDB.
# ==========================================================================
set -e

DB_ENGINE="${DB_ENGINE:-mysql}"

# shellcheck source=/dev/null
source "/usr/local/share/bagisto/db/engine.sh"

log() {
    echo "[build-install] $*"
}

log "Database engine: ${DB_ENGINE_NAME}"

log "Initialising the data directory..."
db_build_init

log "Starting ${DB_ENGINE_NAME}..."
db_build_start

log "Waiting for ${DB_ENGINE_NAME} to accept connections..."
for i in $(seq 1 60); do
    if db_build_wait; then
        log "${DB_ENGINE_NAME} is ready."
        break
    fi

    if [ "$i" -eq 60 ]; then
        log "ERROR: ${DB_ENGINE_NAME} did not start within 60 seconds."
        exit 1
    fi

    sleep 1
done

log "Creating the database and its user..."
db_build_provision

cd /var/www/bagisto

log "Generating the application key..."
php artisan key:generate --force --no-interaction

log "Running the Bagisto installer..."
php artisan bagisto:install --skip-env-check --skip-admin-creation --skip-github-star

log "Seeding demo products..."
php artisan db:seed --class="Webkul\\Installer\\Database\\Seeders\\ProductTableSeeder"

log "Building the search indexes..."
php artisan indexer:index --mode=full

log "Shutting ${DB_ENGINE_NAME} down..."
db_build_stop

log "Bagisto installation complete."
