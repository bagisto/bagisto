#!/bin/bash

# RAM Plaza - Backup Script
# Creates backup of database and uploaded files

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
BACKUP_DIR="$HOME/backups/ramplaza"
DATE=$(date +%Y%m%d_%H%M%S)
DB_CONTAINER="ramplaza-mysql"
DB_NAME="ram_plaza"
DB_USER="ramplaza"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}RAM Plaza - Backup${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
echo -e "${YELLOW}Backing up database...${NC}"
docker exec $DB_CONTAINER mysqldump \
    -u $DB_USER \
    -p$(docker exec $DB_CONTAINER printenv DB_PASSWORD) \
    $DB_NAME \
    | gzip > $BACKUP_DIR/db-$DATE.sql.gz
echo -e "${GREEN}✓ Database backup: $BACKUP_DIR/db-$DATE.sql.gz${NC}"

# Backup storage directory
echo -e "${YELLOW}Backing up storage files...${NC}"
tar -czf $BACKUP_DIR/storage-$DATE.tar.gz \
    -C $(pwd) \
    storage/app
echo -e "${GREEN}✓ Storage backup: $BACKUP_DIR/storage-$DATE.tar.gz${NC}"

# List backups
echo ""
echo -e "${GREEN}Available backups:${NC}"
ls -lh $BACKUP_DIR | tail -10

echo ""
echo -e "${GREEN}Backup complete!${NC}"
echo ""

# Optional: Clean old backups (keep last 7 days)
find $BACKUP_DIR -name "db-*.sql.gz" -mtime +7 -delete
find $BACKUP_DIR -name "storage-*.tar.gz" -mtime +7 -delete
