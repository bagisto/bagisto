#!/bin/bash
# ===========================================================================
# entrypoint.sh — Prepares the container, then hands off to Supervisor.
#
# The image ships with a database already installed and populated. Leaving
# DB_HOST alone uses it. Pointing DB_HOST somewhere else switches to that
# server instead and leaves the bundled one stopped, which is what a
# deployment with a managed database wants.
#
# Which database is bundled is decided at build time — see shared/db/ — and
# this script only talks to it through the engine driver's contract.
# ===========================================================================
set -e

APP_DIR="/var/www/bagisto"

# shellcheck source=/dev/null
source "/usr/local/share/bagisto/db/engine.sh"

log() {
    echo "[bagisto-entrypoint] $(date '+%Y-%m-%d %H:%M:%S') $*"
}

# ---------------------------------------------------------------------------
# Connection Settings
#
# Each one defaults to the bundled database.
# ---------------------------------------------------------------------------
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

# ---------------------------------------------------------------------------
# Scheduler
#
# Supervisor starts cron, and with it Laravel's scheduler, unless this is
# false. Containers that share a database each run every task, so all but one
# of them should set it to false.
# ---------------------------------------------------------------------------
export SCHEDULER_AUTOSTART="${SCHEDULER_AUTOSTART:-true}"

log "Scheduler: autostart=${SCHEDULER_AUTOSTART}"

# ---------------------------------------------------------------------------
# Job Logs
#
# The queue worker, Reverb and the scheduler file their output by day under
# /var/log/bagisto/<job>/ and keep JOB_LOG_DAYS days of it. Cron hands its
# jobs none of this environment, so the value is written into the crontab.
# They all write as www-data, so their folders are handed to it, a mounted
# volume's included.
# ---------------------------------------------------------------------------
export JOB_LOG_DAYS="${JOB_LOG_DAYS:-14}"

if ! [[ "$JOB_LOG_DAYS" =~ ^[0-9]+$ ]]; then
    log "ERROR: JOB_LOG_DAYS must be a whole number of days, not '${JOB_LOG_DAYS}'."
    exit 1
fi

sed -i "s/^JOB_LOG_DAYS=.*/JOB_LOG_DAYS=${JOB_LOG_DAYS}/" /etc/cron.d/bagisto

install -d -o www-data -g www-data /var/log/bagisto/queue /var/log/bagisto/reverb /var/log/bagisto/scheduler

log "Job logs: /var/log/bagisto/{queue,reverb,scheduler}/YYYY-MM-DD.log, JOB_LOG_DAYS=${JOB_LOG_DAYS}"

# ---------------------------------------------------------------------------
# Apply Runtime Overrides to .env
# ---------------------------------------------------------------------------
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
[ -n "$APP_TIMEZONE" ] && sed -i "s|^APP_TIMEZONE=.*|APP_TIMEZONE=${APP_TIMEZONE}|" .env

# The image runs a queue worker, so queued work belongs on a real connection
# rather than in the request that dispatched it. The build installed with
# `.env.example`'s `sync`, which is right for an installation without a
# worker; it is switched here, after the install, not before it.
sed -i "s/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}/" .env

# ---------------------------------------------------------------------------
# Real-Time Notifications
#
# Reverb pushes admin notifications live unless BROADCAST_CONNECTION names
# another connection. As in a production deployment, it listens on
# 127.0.0.1:8080 and the web server proxies it on the store's own address, so
# the browser and PHP both reach it at APP_URL. REVERB_HOST, REVERB_PORT and
# REVERB_SCHEME follow APP_URL unless given, as for a websocket host of its
# own. The app id, key and secret are generated once and kept in .env, unless
# given.
# ---------------------------------------------------------------------------
set_env() {
    if grep -q "^$1=" .env; then
        sed -i "s|^$1=.*|$1=$2|" .env
    else
        echo "$1=$2" >> .env
    fi
}

env_value() {
    grep "^$1=" .env | head -n 1 | cut -d= -f2-
}

# A plain http address on a port other than 80, as on a trial run mapped with
# -p 8080:80, is answered on that port inside the container too, so PHP can
# publish to the address the browser uses. Each image carries one web server.
listen_on_port() {
    local nginx_conf=/etc/nginx/conf.d/bagisto.conf
    local apache_ports=/etc/apache2/ports.conf
    local apache_site=/etc/apache2/sites-available/000-default.conf
    local litespeed_conf=/usr/local/lsws/conf/httpd_config.conf

    if [ -f "$nginx_conf" ] && ! grep -q "listen $1;" "$nginx_conf"; then
        sed -i "s/^    listen 80 default_server;$/&\n    listen $1;/" "$nginx_conf"
    fi

    if [ -f "$apache_ports" ] && ! grep -q "^Listen $1$" "$apache_ports"; then
        echo "Listen $1" >> "$apache_ports"

        sed -i "s/^<VirtualHost \*:80>$/<VirtualHost *:80 *:$1>/" "$apache_site"
    fi

    if [ -f "$litespeed_conf" ] && ! grep -q "address *\*:$1$" "$litespeed_conf"; then
        printf '\nlistener Store%s {\n  address                 *:%s\n  secure                  0\n  map                     bagisto *\n}\n' "$1" "$1" >> "$litespeed_conf"
    fi
}

BROADCAST_CONNECTION="${BROADCAST_CONNECTION:-reverb}"

set_env BROADCAST_CONNECTION "$BROADCAST_CONNECTION"

if [ "$BROADCAST_CONNECTION" = "reverb" ]; then
    for name in REVERB_APP_ID REVERB_APP_KEY REVERB_APP_SECRET; do
        value="${!name:-$(env_value "$name")}"

        set_env "$name" "${value:-$(php -r 'echo bin2hex(random_bytes(16));')}"
    done

    store_url="$(env_value APP_URL)"
    store_scheme="${store_url%%://*}"
    store_address="${store_url#*://}"
    store_address="${store_address%%/*}"
    store_port="${store_address##*:}"

    if [ "$store_port" = "$store_address" ]; then
        store_port=$([ "$store_scheme" = "https" ] && echo 443 || echo 80)
    fi

    set_env REVERB_HOST "${REVERB_HOST:-${store_address%%:*}}"
    set_env REVERB_PORT "${REVERB_PORT:-$store_port}"
    set_env REVERB_SCHEME "${REVERB_SCHEME:-$store_scheme}"

    if [ "$(env_value REVERB_SCHEME)" = "http" ] && [ "$(env_value REVERB_PORT)" != "80" ]; then
        listen_on_port "$(env_value REVERB_PORT)"
    fi

    export REVERB_AUTOSTART=true

    log "Broadcasting: reverb, reached at $(env_value REVERB_SCHEME)://$(env_value REVERB_HOST):$(env_value REVERB_PORT)"
else
    export REVERB_AUTOSTART=false

    log "Broadcasting: ${BROADCAST_CONNECTION}, Reverb stopped"
fi

# ---------------------------------------------------------------------------
# Rebuild the Cached Configuration
#
# The overrides above have just invalidated it.
# ---------------------------------------------------------------------------
log "Rebuilding the cached configuration..."

php artisan optimize:clear --no-interaction 2>/dev/null || true
php artisan optimize --no-interaction 2>/dev/null || true

# Those ran as root, while PHP, the queue worker and the scheduler run as
# www-data. A file they created, such as the log a cache clear opens before the
# bundled database is up, is handed over, since a log owned by root refuses
# every later write and every exception report along with it.
find storage/logs storage/framework bootstrap/cache -user root -exec chown www-data:www-data {} + 2>/dev/null || true

# ---------------------------------------------------------------------------
# Wait for an External Database
#
# An external database has to be reachable before the web server starts.
# ---------------------------------------------------------------------------
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
