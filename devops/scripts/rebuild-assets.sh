#!/bin/bash
# RAM Plaza - Asset Rebuild Script
# This script compiles assets and clears all caches to ensure new assets are loaded

set -e

echo "=== RAM Plaza Asset Rebuild ==="
echo ""

# Step 1: Compile assets
echo "[1/6] Compiling Shop assets..."
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm run build"

# Step 2: Clear all Laravel caches
echo ""
echo "[2/6] Clearing Laravel caches..."
docker exec ramplaza-app php artisan optimize:clear
docker exec ramplaza-app php artisan responsecache:clear

# Step 3: Clear file cache manually
echo ""
echo "[3/6] Clearing file cache..."
docker exec ramplaza-app rm -rf /var/www/html/storage/framework/cache/data/*
docker exec ramplaza-app rm -rf /var/www/html/storage/framework/views/*

# Step 4: Create OPcache reset script
echo ""
echo "[4/6] Creating OPcache reset script..."
docker exec ramplaza-app bash -c 'cat > /var/www/html/public/reset-opcache.php << "EOFPHP"
<?php
if (function_exists("opcache_reset")) {
    opcache_reset();
    echo "OPcache reset successfully";
} else {
    echo "OPcache not available";
}
EOFPHP
'

# Step 5: Reset OPcache via HTTP (required because CLI and FPM have separate caches)
echo ""
echo "[5/6] Resetting OPcache via HTTP..."
sleep 2
curl -s "http://127.0.0.1:8082/reset-opcache.php"
echo ""

# Step 6: Verify
echo ""
echo "[6/6] Verifying..."
NEW_HASH=$(docker exec ramplaza-app cat /var/www/html/public/themes/shop/default/build/manifest.json | grep -o '"file": "assets/app-[^"]*\.css"' | head -1 | grep -o 'app-[^"]*')
echo "New CSS file: $NEW_HASH"

# Test via HTTP
HTTP_HASH=$(curl -s "http://127.0.0.1:8082/" -H "Host: plaza.redactivamexico.net" 2>&1 | grep -o "app-[A-Za-z0-9_-]*\.css" | head -1)
echo "CSS in HTTP response: $HTTP_HASH"

if [ "$NEW_HASH" == "$HTTP_HASH" ]; then
    echo ""
    echo "✓ Success! New assets are being served correctly."
else
    echo ""
    echo "✗ Warning: CSS hash mismatch. Trying additional restart..."
    docker restart ramplaza-webserver
    sleep 5
    HTTP_HASH=$(curl -s "http://127.0.0.1:8082/" -H "Host: plaza.redactivamexico.net" 2>&1 | grep -o "app-[A-Za-z0-9_-]*\.css" | head -1)
    echo "CSS in HTTP response after nginx restart: $HTTP_HASH"
fi

echo ""
echo "=== Done ==="
