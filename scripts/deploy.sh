#!/usr/bin/env bash
# =============================================================================
# Bagisto Production Deploy Script
# Usage: bash scripts/deploy.sh
# Run from the project root after configuring .env
# =============================================================================
set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_DIR"

log() { echo "[deploy] $(date '+%Y-%m-%d %H:%M:%S') $*"; }
die() { echo "[deploy] ERROR: $*" >&2; exit 1; }

# ---------------------------------------------------------------------------
# Pre-flight checks
# ---------------------------------------------------------------------------
[ -f ".env" ] || die ".env not found. Copy .env.example and configure it first."

APP_KEY=$(grep -E '^APP_KEY=' .env | cut -d= -f2)
[ -n "$APP_KEY" ] || die "APP_KEY is empty. Run: php artisan key:generate"

APP_DEBUG=$(grep -E '^APP_DEBUG=' .env | cut -d= -f2)
[ "$APP_DEBUG" = "false" ] || die "APP_DEBUG must be false for production"

log "Starting production deployment..."

# ---------------------------------------------------------------------------
# 1. PHP dependencies (no dev)
# ---------------------------------------------------------------------------
log "[1/8] Installing PHP dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ---------------------------------------------------------------------------
# 2. Database migrations
# ---------------------------------------------------------------------------
log "[2/8] Running database migrations..."
php artisan migrate --force --no-interaction

# ---------------------------------------------------------------------------
# 3. Storage symlink
# ---------------------------------------------------------------------------
log "[3/8] Creating storage symlink..."
php artisan storage:link --no-interaction 2>/dev/null || true

# ---------------------------------------------------------------------------
# 4. Clear and re-cache all
# ---------------------------------------------------------------------------
log "[4/8] Caching config, routes, and views..."
php artisan optimize:clear
php artisan optimize
php artisan view:cache

# ---------------------------------------------------------------------------
# 5. Build frontend assets
# ---------------------------------------------------------------------------
log "[5/8] Building Admin assets..."
(cd packages/Webkul/Admin && npm ci --silent && npm run build)

log "[5/8] Building Shop assets..."
(cd packages/Webkul/Shop && npm ci --silent && npm run build)

# ---------------------------------------------------------------------------
# 6. Set correct permissions
# ---------------------------------------------------------------------------
log "[6/8] Setting permissions..."
chmod -R 775 storage bootstrap/cache
find storage bootstrap/cache -type d -exec chmod g+s {} +

# ---------------------------------------------------------------------------
# 7. Run tests (optional — comment out if slow)
# ---------------------------------------------------------------------------
# log "[7/8] Running test suite..."
# vendor/bin/pest --no-coverage

# ---------------------------------------------------------------------------
# 8. Audit dependencies for known vulnerabilities
# ---------------------------------------------------------------------------
log "[8/8] Checking for vulnerable dependencies..."
composer audit || log "WARNING: composer audit found issues — review before going live"

log "Deployment complete!"
log ""
log "Next steps if using queue worker:"
log "  php artisan queue:work database --sleep=3 --tries=3 --daemon"
log ""
log "Next steps if using scheduler:"
log "  Add to crontab: * * * * * php $APP_DIR/artisan schedule:run >> /dev/null 2>&1"
