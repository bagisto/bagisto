#!/bin/bash

# RAM Plaza - Production Deployment Script
# Run this script from the project root: ./devops/scripts/deploy.sh

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}RAM Plaza - Production Deployment${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if running from project root
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: Must run from project root directory${NC}"
    echo "Usage: ./devops/scripts/deploy.sh"
    exit 1
fi

# Check if .env.production exists
if [ ! -f "devops/docker/.env.production" ]; then
    echo -e "${RED}Error: devops/docker/.env.production not found${NC}"
    echo "Copy from .env.production.example and configure it first"
    exit 1
fi

# Function to ask for confirmation
confirm() {
    read -p "$1 (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${YELLOW}Deployment cancelled${NC}"
        exit 0
    fi
}

# Step 1: Pull latest code
echo -e "${YELLOW}Step 1: Pulling latest code from git...${NC}"
git pull origin main
echo -e "${GREEN}✓ Code updated${NC}"
echo ""

# Step 2: Install/update dependencies
echo -e "${YELLOW}Step 2: Installing dependencies...${NC}"
docker run --rm \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    composer:latest \
    composer install --no-dev --optimize-autoloader --prefer-dist
echo -e "${GREEN}✓ Dependencies installed${NC}"
echo ""

# Step 3: Build assets
echo -e "${YELLOW}Step 3: Building assets...${NC}"
docker compose -f docker-compose.prod.yml run --rm app npm ci
docker compose -f docker-compose.prod.yml run --rm app npm run build
echo -e "${GREEN}✓ Assets built${NC}"
echo ""

# Step 4: Stop containers (if running)
echo -e "${YELLOW}Step 4: Stopping containers...${NC}"
docker compose -f docker-compose.prod.yml down
echo -e "${GREEN}✓ Containers stopped${NC}"
echo ""

# Step 5: Start containers
echo -e "${YELLOW}Step 5: Starting containers...${NC}"
docker compose -f docker-compose.prod.yml up -d
echo -e "${GREEN}✓ Containers started${NC}"
echo ""

# Step 6: Wait for database
echo -e "${YELLOW}Step 6: Waiting for database...${NC}"
sleep 10
echo -e "${GREEN}✓ Database ready${NC}"
echo ""

# Step 7: Run migrations
echo -e "${YELLOW}Step 7: Running migrations...${NC}"
confirm "Run database migrations?"
docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force
echo -e "${GREEN}✓ Migrations complete${NC}"
echo ""

# Step 8: Clear and cache configuration
echo -e "${YELLOW}Step 8: Optimizing application...${NC}"
docker compose -f docker-compose.prod.yml exec -T app php artisan config:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan route:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan view:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan optimize
echo -e "${GREEN}✓ Application optimized${NC}"
echo ""

# Step 9: Create storage link
echo -e "${YELLOW}Step 9: Creating storage link...${NC}"
docker compose -f docker-compose.prod.yml exec -T app php artisan storage:link 2>/dev/null || true
echo -e "${GREEN}✓ Storage link created${NC}"
echo ""

# Step 10: Set permissions
echo -e "${YELLOW}Step 10: Setting permissions...${NC}"
docker compose -f docker-compose.prod.yml exec -T app chown -R sail:sail storage bootstrap/cache
docker compose -f docker-compose.prod.yml exec -T app chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✓ Permissions set${NC}"
echo ""

# Step 11: Restart queue workers
echo -e "${YELLOW}Step 11: Restarting queue workers...${NC}"
docker compose -f docker-compose.prod.yml restart queue-worker
echo -e "${GREEN}✓ Queue workers restarted${NC}"
echo ""

# Summary
echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Deployment Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Application URL: https://plaza.redactivamexico.net"
echo ""
echo "Verify deployment:"
echo "  - Check website is accessible"
echo "  - Test admin login"
echo "  - Check Docker logs: docker compose -f docker-compose.prod.yml logs -f"
echo ""
