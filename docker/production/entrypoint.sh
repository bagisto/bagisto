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

# ==========================================================================
# Queue configuration (used by supervisord)
# ==========================================================================
export QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"
export QUEUE_WORKER_NUMPROCS="${QUEUE_WORKER_NUMPROCS:-2}"

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

[ -n "$APP_URL" ]        && sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" .env
[ -n "$APP_KEY" ]        && sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
[ -n "$APP_LOCALE" ]     && sed -i "s/^APP_LOCALE=.*/APP_LOCALE=${APP_LOCALE}/" .env
[ -n "$APP_CURRENCY" ]   && sed -i "s/^APP_CURRENCY=.*/APP_CURRENCY=${APP_CURRENCY}/" .env
[ -n "$APP_TIMEZONE" ]   && sed -i "s/^APP_TIMEZONE=.*/APP_TIMEZONE=${APP_TIMEZONE}/" .env
[ -n "$APP_DEBUG" ]      && sed -i "s/^APP_DEBUG=.*/APP_DEBUG=${APP_DEBUG}/" .env
[ -n "$QUEUE_CONNECTION" ] && sed -i "s/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=${QUEUE_CONNECTION}/" .env
[ -n "$CACHE_STORE" ]    && sed -i "s/^CACHE_STORE=.*/CACHE_STORE=${CACHE_STORE}/" .env
[ -n "$MAIL_MAILER" ]    && sed -i "s/^MAIL_MAILER=.*/MAIL_MAILER=${MAIL_MAILER}/" .env
[ -n "$MAIL_HOST" ]      && sed -i "s/^MAIL_HOST=.*/MAIL_HOST=${MAIL_HOST}/" .env
[ -n "$MAIL_PORT" ]      && sed -i "s/^MAIL_PORT=.*/MAIL_PORT=${MAIL_PORT}/" .env
[ -n "$MAIL_USERNAME" ]  && sed -i "s/^MAIL_USERNAME=.*/MAIL_USERNAME=${MAIL_USERNAME}/" .env
[ -n "$MAIL_PASSWORD" ]  && sed -i "s/^MAIL_PASSWORD=.*/MAIL_PASSWORD=${MAIL_PASSWORD}/" .env

# ==========================================================================
# Re-cache config if env vars were overridden
# ==========================================================================
if [ -n "$APP_URL" ] || [ -n "$DB_HOST" ] && ! use_internal_mysql; then
    log "Re-caching configuration after env overrides..."
    php artisan optimize:clear --no-interaction 2>/dev/null || true
    php artisan optimize --no-interaction 2>/dev/null || true
fi

# ==========================================================================
# External MySQL: wait for connectivity then run pending migrations
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

    log "Running pending migrations..."
    php artisan migrate --force --no-interaction 2>/dev/null || log "WARNING: migrations failed or already up to date"

    log "Creating storage symlink..."
    php artisan storage:link --no-interaction 2>/dev/null || true
fi

log "Starting services via Supervisor..."

# ==========================================================================
# Hand off to CMD (supervisord)
# ==========================================================================
exec "$@"
