# Runbooks - RAM Plaza

Procedimientos operacionales para el mantenimiento de RAM Plaza.

---

## Cache y Optimización

### Limpiar toda la cache
```bash
docker exec ramplaza-app php artisan optimize:clear
```

### Limpiar cache específica
```bash
# Cache de aplicación
docker exec ramplaza-app php artisan cache:clear

# Cache de configuración
docker exec ramplaza-app php artisan config:clear

# Cache de vistas
docker exec ramplaza-app php artisan view:clear

# Cache de rutas
docker exec ramplaza-app php artisan route:clear
```

### Optimizar para producción
```bash
docker exec ramplaza-app php artisan optimize
docker exec ramplaza-app php artisan view:cache
docker exec ramplaza-app php artisan config:cache
docker exec ramplaza-app php artisan route:cache
```

---

## Contenedores Docker

### Ver estado de contenedores
```bash
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" | grep ramplaza
```

### Reiniciar servicios
```bash
# Todos los servicios
cd ~/apps/RamPlaza && docker compose -f docker-compose.prod.yml restart

# Servicio específico
docker restart ramplaza-app
docker restart ramplaza-webserver
docker restart ramplaza-queue
```

### Ver logs
```bash
# App Laravel
docker logs ramplaza-app --tail 100 -f

# Nginx
docker logs ramplaza-webserver --tail 100 -f

# Queue worker
docker logs ramplaza-queue --tail 100 -f

# Laravel logs (archivo)
docker exec ramplaza-app tail -f storage/logs/laravel.log
```

### Reconstruir contenedor (después de cambios en Dockerfile)
```bash
cd ~/apps/RamPlaza
docker compose -f docker-compose.prod.yml build app
docker compose -f docker-compose.prod.yml up -d
```

---

## Base de Datos

### Ejecutar migraciones
```bash
docker exec ramplaza-app php artisan migrate --force
```

### Rollback última migración
```bash
docker exec ramplaza-app php artisan migrate:rollback
```

### Backup de base de datos
```bash
docker exec ramplaza-mysql mysqldump -u ramplaza -p$DB_PASSWORD ram_plaza > backup-$(date +%Y%m%d-%H%M%S).sql
```

### Restore de backup
```bash
cat backup-YYYYMMDD-HHMMSS.sql | docker exec -i ramplaza-mysql mysql -u ramplaza -p$DB_PASSWORD ram_plaza
```

### Acceder a MySQL
```bash
docker exec -it ramplaza-mysql mysql -u ramplaza -p ram_plaza
```

---

## Assets y Frontend

### Recompilar assets del Shop
```bash
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm install && npm run build"
docker exec ramplaza-app php artisan view:clear
```

### Recompilar assets del Admin
```bash
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Admin && npm install && npm run build"
docker exec ramplaza-app php artisan view:clear
```

### Recompilar todos los assets
```bash
docker exec ramplaza-app bash -c "cd /var/www/html && npm install && npm run build"
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm install && npm run build"
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Admin && npm install && npm run build"
docker exec ramplaza-app php artisan optimize:clear
```

---

## Deploy de Cambios

### Deploy estándar (sin cambios en assets)
```bash
cd ~/apps/RamPlaza
git pull origin main
docker exec ramplaza-app php artisan migrate --force
docker exec ramplaza-app php artisan optimize:clear
docker exec ramplaza-app php artisan optimize
```

### Deploy con cambios en assets
```bash
cd ~/apps/RamPlaza
git pull origin main
docker exec ramplaza-app php artisan migrate --force

# Recompilar assets afectados
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm install && npm run build"

docker exec ramplaza-app php artisan optimize:clear
docker exec ramplaza-app php artisan optimize
```

### Rollback de deploy
```bash
cd ~/apps/RamPlaza
git log --oneline -5  # Ver commits recientes
git revert HEAD       # Revertir último commit
# O
git reset --hard HEAD~1  # Descartar último commit (cuidado!)

docker exec ramplaza-app php artisan migrate:rollback
docker exec ramplaza-app php artisan optimize:clear
```

---

## Queue Workers

### Ver estado de jobs
```bash
docker exec ramplaza-app php artisan queue:monitor redis
```

### Reiniciar queue worker
```bash
docker restart ramplaza-queue
```

### Procesar jobs manualmente
```bash
docker exec ramplaza-app php artisan queue:work redis --once
```

### Limpiar jobs fallidos
```bash
docker exec ramplaza-app php artisan queue:flush
```

---

## Elasticsearch

### Reindexar productos
```bash
docker exec ramplaza-app php artisan scout:flush "Webkul\Product\Models\Product"
docker exec ramplaza-app php artisan scout:import "Webkul\Product\Models\Product"
```

### Ver estado de Elasticsearch
```bash
curl -X GET "localhost:9200/_cluster/health?pretty"
curl -X GET "localhost:9200/_cat/indices?v"
```

---

## Storage y Uploads

### Verificar symlink de storage
```bash
docker exec ramplaza-app ls -la public/storage
```

### Recrear symlink
```bash
docker exec ramplaza-app php artisan storage:link
```

### Permisos de storage
```bash
docker exec ramplaza-app chmod -R 775 storage bootstrap/cache
docker exec ramplaza-app chown -R www-data:www-data storage bootstrap/cache
```

---

## Nginx (Host)

### Recargar configuración
```bash
sudo nginx -t && sudo systemctl reload nginx
```

### Ver logs
```bash
sudo tail -f /var/log/nginx/plaza-access.log
sudo tail -f /var/log/nginx/plaza-error.log
```

### Renovar certificado SSL
```bash
sudo certbot renew
sudo systemctl reload nginx
```

---

## Monitoreo

### Uso de recursos por contenedor
```bash
docker stats --no-stream | grep ramplaza
```

### Espacio en disco
```bash
df -h
docker system df
```

### Limpiar recursos Docker no usados
```bash
docker system prune -f
docker volume prune -f  # CUIDADO: elimina volúmenes no usados
```
