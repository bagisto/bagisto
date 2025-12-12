#!/bin/bash

# RAM Plaza - Setup Docker Shared Network
# Creates shared network between WoWonder (RAM) and Bagisto for internal communication

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Setting up Docker Shared Network${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Network name (must match docker-compose.prod.yml)
NETWORK_NAME="wowonder_shared"

# Check if network already exists
if docker network inspect $NETWORK_NAME >/dev/null 2>&1; then
    echo -e "${GREEN}✓ Network '$NETWORK_NAME' already exists${NC}"
else
    echo -e "${YELLOW}Creating network '$NETWORK_NAME'...${NC}"
    docker network create $NETWORK_NAME
    echo -e "${GREEN}✓ Network created${NC}"
fi

echo ""
echo -e "${YELLOW}Network Information:${NC}"
docker network inspect $NETWORK_NAME --format '{{.Name}}: Driver={{.Driver}}, Scope={{.Scope}}'

echo ""
echo -e "${YELLOW}Containers connected to this network:${NC}"
docker network inspect $NETWORK_NAME --format '{{range .Containers}}  - {{.Name}}{{"\n"}}{{end}}' 2>/dev/null || echo "  (none yet)"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Next steps:${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "1. If WoWonder (RAM) is running, connect its container:"
echo -e "   ${BLUE}docker network connect $NETWORK_NAME <wowonder-container-name>${NC}"
echo ""
echo "2. Add this network to WoWonder's docker-compose.yml:"
echo -e "   ${BLUE}networks:${NC}"
echo -e "   ${BLUE}  $NETWORK_NAME:${NC}"
echo -e "   ${BLUE}    external: true${NC}"
echo ""
echo "3. Start/restart RAM Plaza:"
echo -e "   ${BLUE}cd ~/apps/RamPlaza${NC}"
echo -e "   ${BLUE}docker compose -f docker-compose.prod.yml up -d${NC}"
echo ""
echo "4. Test connectivity between containers:"
echo -e "   ${BLUE}docker exec ramplaza-app ping <wowonder-container> -c 3${NC}"
echo ""
