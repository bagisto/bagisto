# Mantenimiento - RAM Plaza

Procedimientos de mantenimiento regular.

---

## Mantenimiento Diario

### Verificar estado de servicios
```bash
docker ps --format "table {{.Names}}\t{{.Status}}" | grep ramplaza
```

Todos deben mostrar "Up" y "(healthy)" donde aplique.

### Revisar logs de errores
```bash
# Errores recientes de Laravel
docker exec ramplaza-app tail -20 storage/logs/laravel.log | grep -i error

# Errores de Nginx
docker logs ramplaza-webserver --since 24h 2>&1 | grep -i error
```

---

## Mantenimiento Semanal

### Backup de base de datos
```bash
# Crear directorio de backups si no existe
mkdir -p ~/backups/ramplaza

# Backup
docker exec ramplaza-mysql mysqldump -u ramplaza -p$DB_PASSWORD ram_plaza | gzip > ~/backups/ramplaza/db-$(date +%Y%m%d).sql.gz

# Mantener solo últimos 7 backups
ls -t ~/backups/ramplaza/db-*.sql.gz | tail -n +8 | xargs -r rm
```

### Backup de uploads
```bash
tar -czf ~/backups/ramplaza/storage-$(date +%Y%m%d).tar.gz -C ~/apps/RamPlaza storage/app/public

# Mantener solo últimos 4 backups (1 mes)
ls -t ~/backups/ramplaza/storage-*.tar.gz | tail -n +5 | xargs -r rm
```

### Limpiar cache de Laravel
```bash
docker exec ramplaza-app php artisan cache:clear
```

### Revisar espacio en disco
```bash
df -h /home
docker system df
```

---

## Mantenimiento Mensual

### Actualizar dependencias de seguridad
```bash
cd ~/apps/RamPlaza

# Ver updates disponibles
docker exec ramplaza-app composer outdated

# Actualizar solo parches de seguridad
docker exec ramplaza-app composer update --prefer-stable --no-dev
```

### Limpiar recursos Docker no usados
```bash
docker system prune -f
docker image prune -f
```

### Verificar certificados SSL
```bash
sudo certbot certificates
# Renovar si expira en menos de 30 días
sudo certbot renew --dry-run
```

### Verificar logs de errores acumulados
```bash
# Tamaño de logs
du -sh ~/apps/RamPlaza/storage/logs/

# Rotar logs si son muy grandes
docker exec ramplaza-app php artisan log:clear  # Si existe
# O manualmente:
docker exec ramplaza-app truncate -s 0 storage/logs/laravel.log
```

---

## Actualización de Bagisto

### Antes de actualizar

1. **Backup completo:**
```bash
# Base de datos
docker exec ramplaza-mysql mysqldump -u ramplaza -p$DB_PASSWORD ram_plaza > ~/backups/ramplaza/pre-update-$(date +%Y%m%d).sql

# Código
cd ~/apps/RamPlaza
git stash  # Si hay cambios locales
```

2. **Revisar changelog de Bagisto:**
   - https://github.com/bagisto/bagisto/releases
   - Verificar breaking changes

### Proceso de actualización

```bash
cd ~/apps/RamPlaza

# 1. Fetch cambios de upstream
git fetch upstream

# 2. Ver qué cambios hay
git log HEAD..upstream/2.3 --oneline

# 3. Crear rama de update
git checkout -b update/bagisto-$(date +%Y%m%d)

# 4. Merge cambios
git merge upstream/2.3

# 5. Resolver conflictos si hay
# Priorizar: seguridad de Bagisto > customizaciones RAM

# 6. Probar localmente (si es posible)

# 7. Aplicar migraciones
docker exec ramplaza-app php artisan migrate --force

# 8. Recompilar assets si hay cambios
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm install && npm run build"

# 9. Limpiar cache
docker exec ramplaza-app php artisan optimize:clear
docker exec ramplaza-app php artisan optimize

# 10. Verificar funcionamiento
# - Navegación de tienda
# - Carrito y checkout
# - Admin panel
# - Búsqueda

# 11. Si todo bien, merge a main
git checkout main
git merge update/bagisto-$(date +%Y%m%d)
git push origin main
```

### Rollback si falla

```bash
# Restaurar base de datos
cat ~/backups/ramplaza/pre-update-YYYYMMDD.sql | docker exec -i ramplaza-mysql mysql -u ramplaza -p$DB_PASSWORD ram_plaza

# Restaurar código
git checkout main
git reset --hard HEAD~1  # Descartar merge

# Reiniciar servicios
docker compose -f docker-compose.prod.yml restart
```

---

## Monitoreo de Performance

### Métricas a revisar

```bash
# Uso de CPU/RAM por contenedor
docker stats --no-stream | grep ramplaza

# Conexiones a MySQL
docker exec ramplaza-mysql mysql -u ramplaza -p$DB_PASSWORD -e "SHOW STATUS LIKE 'Threads_connected';"

# Redis memoria
docker exec ramplaza-redis redis-cli info memory | grep used_memory_human

# Elasticsearch salud
curl -s localhost:9200/_cluster/health | jq '.status'
```

### Alertas recomendadas

Configurar alertas para:
- Disco > 80% usado
- RAM > 90% usada
- Contenedor reiniciándose
- Certificado SSL expirando en < 14 días
- Backup no ejecutado en > 24h

---

## Checklist de Mantenimiento

### Diario
- [ ] Verificar que todos los contenedores están "Up"
- [ ] Revisar errores críticos en logs

### Semanal
- [ ] Backup de base de datos
- [ ] Backup de storage/uploads
- [ ] Limpiar cache
- [ ] Revisar espacio en disco

### Mensual
- [ ] Actualizar dependencias de seguridad
- [ ] Limpiar Docker (prune)
- [ ] Verificar certificados SSL
- [ ] Rotar logs grandes
- [ ] Revisar updates de Bagisto

### Trimestral
- [ ] Probar restore de backup
- [ ] Revisar y actualizar documentación
- [ ] Evaluar performance y optimizar
- [ ] Revisar configuración de seguridad
