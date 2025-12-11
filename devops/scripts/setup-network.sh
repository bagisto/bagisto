#!/bin/bash

# RAM Plaza - Setup Docker Shared Network
# Creates shared network between WoWonder and Bagisto for SSO communication

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Setting up Docker Shared Network${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Network name
NETWORK_NAME="ram_shared"

# Check if network already exists
if docker network inspect $NETWORK_NAME >/dev/null 2>&1; then
    echo -e "${YELLOW}Network '$NETWORK_NAME' already exists${NC}"
else
    echo -e "${YELLOW}Creating network '$NETWORK_NAME'...${NC}"
    docker network create $NETWORK_NAME
    echo -e "${GREEN}✓ Network created${NC}"
fi

echo ""
echo -e "${YELLOW}Network Information:${NC}"
docker network inspect $NETWORK_NAME | grep -E "Name|Driver|Subnet|Gateway"

echo ""
echo -e "${GREEN}Next steps:${NC}"
echo "1. Connect WoWonder container to this network:"
echo "   ${YELLOW}docker network connect $NETWORK_NAME ram_app_1${NC}"
echo ""
echo "2. Bagisto will automatically connect when started (configured in docker-compose)"
echo ""
echo "3. Test connectivity:"
echo "   ${YELLOW}docker exec ramplaza-app ping ram_app_1 -c 3${NC}"
echo ""
