#!/bin/bash
# ==========================================================================
# entrypoint.sh — Prepares the container, then hands off to Supervisor.
#
# The image ships with a database already installed and populated. Leaving
# DB_HOST alone uses it. Pointing DB_HOST somewhere else switches to that
# server instead and leaves the bundled one stopped, which is what a
# deployment with a managed database wants.
#
# Which database is bundled is decided at build time — see shared/db/ — and
# this script only talks to it through the engine driver's contract.
# ==========================================================================
set -e

APP_DIR="/var/www/bagisto"

# shellcheck source=/dev/null
source "/usr/local/share/bagisto/db/engine.sh"

log() {
    echo "[bagisto-entrypoint] $(date '+%Y-%m-%d %H:%M:%S') $*"
}

# ==========================================================================
# Connection settings, defaulting to the bundled database
# ==========================================================================
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-$(db_default_port)}"
DB_DATABASE="${DB_DATABASE:-bagisto}"
DB_USERNAME="${DB_USERNAME:-bagisto}"
DB_PASSWORD="${DB_PASSWORD:-bagisto}"
DB_CONNECTION="${DB_CONNECTION:-$(db_connection)}"

uses_bundled_database() {
    [[ "$DB_HOST" == "127.0.0.1" || "$DB_HOST" == "localhost" ]]
}

if uses_bundled_database; then
    log "Database: bundled ${DB_ENGINE_NAME} on port ${DB_PORT}"

    export DB_AUTOSTART=true
else
    log "Database: external ${DB_ENGINE_NAME} at ${DB_HOST}:${DB_PORT}"

    export DB_AUTOSTART=false
fi

# ==========================================================================
# Apply runtime overrides to .env
# ==========================================================================
cd "$APP_DIR"

log "Applying environment overrides..."

sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=${DB_CONNECTION}/" .env
sed -i "s/^DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
sed -i "s/^DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env

[ -n "$APP_URL" ]      && sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" .env
[ -n "$APP_KEY" ]      && sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
[ -n "$APP_LOCALE" ]   && sed -i "s/^APP_LOCALE=.*/APP_LOCALE=${APP_LOCALE}/" .env
[ -n "$APP_CURRENCY" ] && sed -i "s/^APP_CURRENCY=.*/APP_CURRENCY=${APP_CURRENCY}/" .env
[ -n "$APP_TIMEZONE" ] && sed -i "s/^APP_TIMEZONE=.*/APP_TIMEZONE=${APP_TIMEZONE}/" .env

# ==========================================================================
# Rebuild the cached config when anything above actually changed it
# ==========================================================================
if [ -n "$APP_URL" ] || ! uses_bundled_database; then
    log "Rebuilding the cached configuration..."

    php artisan optimize:clear --no-interaction 2>/dev/null || true
    php artisan optimize --no-interaction 2>/dev/null || true
fi

# ==========================================================================
# An external database has to be reachable before the web server starts
# ==========================================================================
if ! uses_bundled_database; then
    log "Waiting for ${DB_ENGINE_NAME} at ${DB_HOST}:${DB_PORT}..."

    for i in $(seq 1 60); do
        if db_runtime_ping; then
            log "${DB_ENGINE_NAME} is reachable."
            break
        fi

        if [ "$i" -eq 60 ]; then
            log "ERROR: cannot reach ${DB_ENGINE_NAME} at ${DB_HOST}:${DB_PORT} after 60s."
            exit 1
        fi

        sleep 1
    done
fi

log "Starting services via Supervisor..."

exec "$@"
