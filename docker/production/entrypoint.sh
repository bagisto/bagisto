#!/bin/bash
set -e

APP_DIR="/var/www/bagisto"

# ==========================================================================
# Helper: log with timestamp
# ==========================================================================
log() {
    echo "[bagisto-entrypoint] $(date '+%Y-%m-%d %H:%M:%S') $*"
}

# ==========================================================================
# Determine database mode: internal (default) or external
# ==========================================================================
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-bagisto}"
DB_USERNAME="${DB_USERNAME:-bagisto}"
DB_PASSWORD="${DB_PASSWORD:-bagisto}"

use_internal_mysql() {
    [[ "$DB_HOST" == "127.0.0.1" || "$DB_HOST" == "localhost" ]]
}

if use_internal_mysql; then
    log "Mode: INTERNAL MySQL"
    export MYSQL_AUTOSTART=true
else
    log "Mode: EXTERNAL MySQL (${DB_HOST}:${DB_PORT})"
    export MYSQL_AUTOSTART=false
fi

# ==========================================================================
# Update .env with runtime overrides (if any env vars are passed)
# ==========================================================================
cd "$APP_DIR"

log "Applying runtime environment overrides..."
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
# Re-cache config if env vars were overridden
# ==========================================================================
if [ -n "$APP_URL" ] || [ -n "$DB_HOST" ] && ! use_internal_mysql; then
    log "Re-caching configuration after env overrides..."
    php artisan optimize:clear --no-interaction 2>/dev/null || true
    php artisan optimize --no-interaction 2>/dev/null || true
fi

# ==========================================================================
# External MySQL: wait for connectivity before Supervisor starts
# ==========================================================================
if ! use_internal_mysql; then
    log "Waiting for external MySQL at ${DB_HOST}:${DB_PORT}..."
    for i in $(seq 1 60); do
        if php -r "try { new PDO('mysql:host=${DB_HOST};port=${DB_PORT}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'ok'; } catch(Exception \$e) { exit(1); }" 2>/dev/null; then
            log "External MySQL is reachable."
            break
        fi
        if [ "$i" -eq 60 ]; then
            log "ERROR: Cannot reach external MySQL at ${DB_HOST}:${DB_PORT} after 60s"
            exit 1
        fi
        sleep 1
    done
fi

log "Starting services via Supervisor..."

# ==========================================================================
# Hand off to CMD (supervisord)
# ==========================================================================
exec "$@"
