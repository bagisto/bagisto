#!/bin/bash

# RAM Plaza - Sync with Bagisto Upstream
# Checks for updates from Bagisto official repo and helps merge them

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Sincronizando con Bagisto upstream${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if upstream is configured
if ! git remote | grep -q "^upstream$"; then
    echo -e "${YELLOW}⚠️  Remote 'upstream' no configurado${NC}"
    echo ""
    echo "Configurar upstream:"
    echo -e "${BLUE}git remote add upstream https://github.com/bagisto/bagisto.git${NC}"
    exit 1
fi

# Fetch upstream
echo -e "${YELLOW}Fetching upstream...${NC}"
git fetch upstream

# Check if on correct branch
CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" != "2.3" ] && [ "$CURRENT_BRANCH" != "main" ]; then
    echo -e "${YELLOW}⚠️  Estás en rama: $CURRENT_BRANCH${NC}"
    echo "Cambiando a rama 2.3..."
    git checkout 2.3
fi

# Count commits
COMMITS=$(git log 2.3..upstream/2.3 --oneline 2>/dev/null | wc -l)

if [ $COMMITS -eq 0 ]; then
    echo -e "${GREEN}✅ Ya estás actualizado con upstream${NC}"
    exit 0
fi

# Show changes
echo ""
echo -e "${BLUE}📋 Hay $COMMITS commits nuevos en Bagisto upstream/2.3:${NC}"
echo ""
git log 2.3..upstream/2.3 --oneline --color=always | head -20

if [ $COMMITS -gt 20 ]; then
    echo "... y $(($COMMITS - 20)) commits más"
fi

echo ""
read -p "¿Ver detalles de los cambios? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    git log 2.3..upstream/2.3 --stat --color=always | less -R
fi

# Ask to merge
echo ""
read -p "¿Merge estos cambios a rama 2.3? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}Cancelado${NC}"
    exit 0
fi

# Merge to 2.3
echo ""
echo -e "${YELLOW}Merging upstream/2.3 → 2.3...${NC}"
git checkout 2.3
git merge upstream/2.3

if [ $? -ne 0 ]; then
    echo -e "${YELLOW}⚠️  Hay conflictos que resolver${NC}"
    echo "Resuelve los conflictos y luego:"
    echo -e "${BLUE}git add .${NC}"
    echo -e "${BLUE}git commit${NC}"
    exit 1
fi

git push origin 2.3
echo -e "${GREEN}✅ Rama 2.3 actualizada${NC}"

# Next steps
echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Próximos pasos:${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "1. Revisar los cambios que se mergearon"
echo ""
echo "2. Mergear a main:"
echo -e "   ${BLUE}git checkout main${NC}"
echo -e "   ${BLUE}git merge 2.3 -m \"chore: merge Bagisto updates from upstream\"${NC}"
echo ""
echo "3. O crear rama temporal para probar:"
echo -e "   ${BLUE}git checkout main${NC}"
echo -e "   ${BLUE}git checkout -b update/bagisto-$(date +%Y%m%d)${NC}"
echo -e "   ${BLUE}git merge 2.3${NC}"
echo ""
echo "4. Probar localmente:"
echo -e "   ${BLUE}docker compose restart${NC}"
echo ""
