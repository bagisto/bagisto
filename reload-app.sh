#!/bin/sh
# Reload Bagisto after editing PHP/Blade code.
#
# Because opcache.validate_timestamps=0 is set for speed (see docker/php/opcache.ini),
# PHP keeps your old code compiled in memory until opcache is reset. Run this script
# whenever you change PHP/Blade/config/routes and want the changes to take effect.
#
# Usage:  ./reload-app.sh
set -e

SERVICE="laravel.test"

echo "==> Rebuilding Laravel caches (config/routes/events)..."
docker compose exec -T "$SERVICE" php artisan optimize:clear
# NOTE: we deliberately skip `view:cache` here. On this Windows bind-mount, compiling
# every Blade template up-front takes minutes; instead views compile lazily on first
# visit (fast) and changed views are picked up after this script restarts opcache.
docker compose exec -T "$SERVICE" php artisan config:cache
docker compose exec -T "$SERVICE" php artisan route:cache
docker compose exec -T "$SERVICE" php artisan event:cache

echo "==> Restarting dev server (resets opcache)..."
docker compose restart "$SERVICE" >/dev/null

echo "==> Waiting for app to come back up..."
i=0
while [ $i -lt 40 ]; do
  CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8111/admin/login || echo 000)
  if [ "$CODE" = "200" ]; then echo "    ready (HTTP $CODE)"; break; fi
  i=$((i+1)); sleep 2
done

echo "==> Warming opcache (first hits compile your fresh code)..."
for u in / /admin/login; do
  for n in 1 2 3; do curl -s -o /dev/null "http://localhost:8111$u"; done
done
echo "Done. App reloaded and warm."
