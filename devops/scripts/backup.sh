#!/bin/bash

# RAM Plaza - Backup Script
# Creates backup of database and uploaded files
# Usage: ./devops/scripts/backup.sh [--quiet]

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Configuration
BACKUP_DIR="$HOME/backups/ramplaza"
DATE=$(date +%Y%m%d_%H%M%S)
DB_CONTAINER="ramplaza-mysql"
DB_NAME="ram_plaza"
DB_USER="ramplaza"
PROJECT_DIR="$HOME/apps/RamPlaza"

# Quiet mode
QUIET=false
if [ "$1" == "--quiet" ]; then
    QUIET=true
fi

log() {
    if [ "$QUIET" == "false" ]; then
        echo -e "$1"
    fi
}

log "${GREEN}========================================${NC}"
log "${GREEN}RAM Plaza - Backup${NC}"
log "${GREEN}========================================${NC}"
log ""

# Check if running from correct location
if [ ! -d "$PROJECT_DIR" ]; then
    echo -e "${RED}Error: Project directory not found at $PROJECT_DIR${NC}"
    exit 1
fi

# Create backup directory
mkdir -p $BACKUP_DIR

# Get DB password from .env file
if [ -f "$PROJECT_DIR/.env" ]; then
    DB_PASSWORD=$(grep "^DB_PASSWORD=" "$PROJECT_DIR/.env" | cut -d'=' -f2)
elif [ -f "$PROJECT_DIR/devops/docker/.env.production" ]; then
    DB_PASSWORD=$(grep "^DB_PASSWORD=" "$PROJECT_DIR/devops/docker/.env.production" | cut -d'=' -f2)
else
    echo -e "${RED}Error: No .env file found${NC}"
    exit 1
fi

if [ -z "$DB_PASSWORD" ]; then
    echo -e "${RED}Error: DB_PASSWORD not found in .env${NC}"
    exit 1
fi

# Backup database
log "${YELLOW}Backing up database...${NC}"
docker exec $DB_CONTAINER mysqldump \
    -u $DB_USER \
    -p"$DB_PASSWORD" \
    $DB_NAME \
    2>/dev/null \
    | gzip > $BACKUP_DIR/db-$DATE.sql.gz

if [ $? -eq 0 ]; then
    DB_SIZE=$(ls -lh $BACKUP_DIR/db-$DATE.sql.gz | awk '{print $5}')
    log "${GREEN}✓ Database backup: db-$DATE.sql.gz ($DB_SIZE)${NC}"
else
    echo -e "${RED}✗ Database backup failed${NC}"
    exit 1
fi

# Backup storage directory
log "${YELLOW}Backing up storage files...${NC}"
if [ -d "$PROJECT_DIR/storage/app/public" ]; then
    tar -czf $BACKUP_DIR/storage-$DATE.tar.gz \
        -C $PROJECT_DIR \
        storage/app/public 2>/dev/null

    STORAGE_SIZE=$(ls -lh $BACKUP_DIR/storage-$DATE.tar.gz | awk '{print $5}')
    log "${GREEN}✓ Storage backup: storage-$DATE.tar.gz ($STORAGE_SIZE)${NC}"
else
    log "${YELLOW}⚠ No storage/app/public directory found, skipping${NC}"
fi

# Clean old backups (keep last 7 days)
log ""
log "${YELLOW}Cleaning old backups (keeping last 7 days)...${NC}"
DELETED_DB=$(find $BACKUP_DIR -name "db-*.sql.gz" -mtime +7 -delete -print | wc -l)
DELETED_STORAGE=$(find $BACKUP_DIR -name "storage-*.tar.gz" -mtime +7 -delete -print | wc -l)
log "${GREEN}✓ Cleaned $DELETED_DB old database backups, $DELETED_STORAGE old storage backups${NC}"

# List recent backups
log ""
log "${GREEN}Recent backups:${NC}"
ls -lht $BACKUP_DIR | head -10

log ""
log "${GREEN}========================================${NC}"
log "${GREEN}Backup complete!${NC}"
log "${GREEN}========================================${NC}"
log ""
log "Backup location: $BACKUP_DIR"
log ""
log "To restore database:"
log "  gunzip < $BACKUP_DIR/db-$DATE.sql.gz | docker exec -i $DB_CONTAINER mysql -u $DB_USER -p ram_plaza"
log ""
log "To restore storage:"
log "  tar -xzf $BACKUP_DIR/storage-$DATE.tar.gz -C $PROJECT_DIR"
log ""
