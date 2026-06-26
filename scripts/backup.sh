#!/usr/bin/env bash
# =============================================================================
# Bagisto Backup Script
# Usage: bash scripts/backup.sh [output-dir]
# Creates: database dump + storage archive, retains last 7 backups
# =============================================================================
set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACKUP_DIR="${1:-${APP_DIR}/backups}"
TIMESTAMP=$(date '+%Y%m%d_%H%M%S')
RETAIN_DAYS=${BACKUP_KEEP_DAYS:-7}

log() { echo "[backup] $(date '+%Y-%m-%d %H:%M:%S') $*"; }
die() { echo "[backup] ERROR: $*" >&2; exit 1; }

mkdir -p "$BACKUP_DIR"

# ---------------------------------------------------------------------------
# Load env vars
# ---------------------------------------------------------------------------
[ -f "${APP_DIR}/.env" ] || die ".env not found"

DB_HOST=$(grep -E '^DB_HOST=' "${APP_DIR}/.env" | cut -d= -f2)
DB_PORT=$(grep -E '^DB_PORT=' "${APP_DIR}/.env" | cut -d= -f2 || echo "3306")
DB_DATABASE=$(grep -E '^DB_DATABASE=' "${APP_DIR}/.env" | cut -d= -f2)
DB_USERNAME=$(grep -E '^DB_USERNAME=' "${APP_DIR}/.env" | cut -d= -f2)
DB_PASSWORD=$(grep -E '^DB_PASSWORD=' "${APP_DIR}/.env" | cut -d= -f2)

# ---------------------------------------------------------------------------
# Database backup
# ---------------------------------------------------------------------------
DB_FILE="${BACKUP_DIR}/db_${TIMESTAMP}.sql.gz"
log "Backing up database '${DB_DATABASE}' to ${DB_FILE}..."

MYSQL_PWD="${DB_PASSWORD}" mysqldump \
    --host="${DB_HOST}" \
    --port="${DB_PORT}" \
    --user="${DB_USERNAME}" \
    --single-transaction \
    --quick \
    --lock-tables=false \
    "${DB_DATABASE}" | gzip > "${DB_FILE}"

log "Database backup complete: $(du -sh "${DB_FILE}" | cut -f1)"

# ---------------------------------------------------------------------------
# Storage backup (uploads, media, logs)
# ---------------------------------------------------------------------------
STORAGE_FILE="${BACKUP_DIR}/storage_${TIMESTAMP}.tar.gz"
log "Backing up storage to ${STORAGE_FILE}..."

tar -czf "${STORAGE_FILE}" \
    -C "${APP_DIR}" \
    --exclude="storage/logs/*.log" \
    storage/app

log "Storage backup complete: $(du -sh "${STORAGE_FILE}" | cut -f1)"

# ---------------------------------------------------------------------------
# Rotate old backups
# ---------------------------------------------------------------------------
log "Removing backups older than ${RETAIN_DAYS} days..."
find "${BACKUP_DIR}" -name "db_*.sql.gz" -mtime "+${RETAIN_DAYS}" -delete
find "${BACKUP_DIR}" -name "storage_*.tar.gz" -mtime "+${RETAIN_DAYS}" -delete

log "Backup finished. Files in ${BACKUP_DIR}:"
ls -lh "${BACKUP_DIR}" | tail -10
