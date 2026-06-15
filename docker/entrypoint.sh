#!/bin/bash
set -e

APP_DIR="/var/www/html"

log() {
    echo "[bagisto-docker] $(date '+%Y-%m-%d %H:%M:%S') $*"
}

update_env() {
    local key="$1"
    local value="$2"

    if [ -z "$value" ]; then
        return
    fi

    if grep -q "^${key}=" "$APP_DIR/.env"; then
        sed -i "s|^${key}=.*|${key}=${value}|" "$APP_DIR/.env"
    else
        echo "${key}=${value}" >> "$APP_DIR/.env"
    fi
}

wait_for_mysql() {
    local host="${DB_HOST:-mysql}"
    local port="${DB_PORT:-3306}"
    local user="${DB_USERNAME:-bagisto}"
    local pass="${DB_PASSWORD:-bagisto}"

    log "Waiting for MySQL at ${host}:${port}..."

    for i in $(seq 1 90); do
        if php -r "try { new PDO('mysql:host=${host};port=${port}', '${user}', '${pass}'); echo 'ok'; } catch (Exception \$e) { exit(1); }" 2>/dev/null; then
            log "MySQL is ready."

            return
        fi

        sleep 2
    done

    log "ERROR: MySQL not reachable at ${host}:${port}"
    exit 1
}

is_installed() {
    php -r "
        require '${APP_DIR}/vendor/autoload.php';
        \$app = require '${APP_DIR}/bootstrap/app.php';
        \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        echo app(Webkul\Installer\Helpers\DatabaseManager::class)->isInstalled() ? '1' : '0';
    " 2>/dev/null || echo "0"
}

cd "$APP_DIR"

if [ ! -f .env ]; then
    log "Creating .env from .env.example..."
    cp .env.example .env
fi

update_env "DB_CONNECTION" "${DB_CONNECTION:-mysql}"
update_env "DB_HOST" "${DB_HOST:-mysql}"
update_env "DB_PORT" "${DB_PORT:-3306}"
update_env "DB_DATABASE" "${DB_DATABASE:-bagisto}"
update_env "DB_USERNAME" "${DB_USERNAME:-bagisto}"
update_env "DB_PASSWORD" "${DB_PASSWORD:-bagisto}"
update_env "REDIS_HOST" "${REDIS_HOST:-redis}"
update_env "REDIS_PORT" "${REDIS_PORT:-6379}"
update_env "ELASTICSEARCH_HOST" "${ELASTICSEARCH_HOST:-http://elasticsearch:9200}"
update_env "MAIL_HOST" "${MAIL_HOST:-mailpit}"
update_env "MAIL_PORT" "${MAIL_PORT:-1025}"
update_env "APP_URL" "${APP_URL:-http://localhost:8090}"
update_env "ASSET_URL" "${ASSET_URL:-${APP_URL:-http://localhost:8090}}"
update_env "RESPONSE_CACHE_ENABLED" "${RESPONSE_CACHE_ENABLED:-false}"
update_env "NEO4J_URI" "${NEO4J_URI:-bolt://neo4j:7687}"
update_env "NEO4J_USERNAME" "${NEO4J_USERNAME:-neo4j}"
update_env "NEO4J_PASSWORD" "${NEO4J_PASSWORD:-password}"
update_env "NEO4J_MCP_TRANSPORT" "${NEO4J_MCP_TRANSPORT:-stdio}"

if [ ! -f vendor/autoload.php ]; then
    log "Installing Composer dependencies (first run may take a few minutes)..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

wait_for_mysql

if [ "$(is_installed)" != "1" ]; then
    log "Bagisto is not installed yet — running first-time setup..."

    if ! grep -q '^APP_KEY=base64:' .env; then
        php artisan key:generate --force --no-interaction
    fi

    php artisan bagisto:install \
        --skip-env-check \
        --skip-admin-creation \
        --skip-cloud-promotion \
        --no-interaction

    log "Seeding sample products and images..."
    php artisan db:seed \
        --class="Webkul\\Installer\\Database\\Seeders\\ProductTableSeeder" \
        --no-interaction

    log "Running product indexers..."
    php artisan indexer:index --mode=full --no-interaction || true
else
    log "Bagisto is already installed — running migrations..."
    php artisan migrate --force --no-interaction || true
fi

php artisan storage:link --force --no-interaction 2>/dev/null || true
php artisan optimize:clear --no-interaction || true

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

log "Starting Nginx and PHP-FPM..."

exec "$@"
