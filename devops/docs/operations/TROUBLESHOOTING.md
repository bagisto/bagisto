# Troubleshooting - RAM Plaza

Problemas conocidos y sus soluciones.

---

## Imágenes y Media

### Imágenes 404 (no se muestran)

**Síntomas:** Las imágenes subidas desde el admin no se muestran en la tienda.

**Causa:** Nginx no tiene acceso al volumen de storage.

**Solución:**
1. Verificar que el volumen está montado en `docker-compose.prod.yml`:
```yaml
nginx:
  volumes:
    - ./storage/app/public:/var/www/html/public/storage
```

2. Recrear el symlink:
```bash
docker exec ramplaza-app php artisan storage:link
```

3. Verificar permisos:
```bash
docker exec ramplaza-app chmod -R 775 storage
```

### Imágenes no cargan hasta redimensionar ventana (Lazy Loading Bug)

**Síntomas:** Las imágenes del carrusel y productos no aparecen al cargar la página, pero sí aparecen si cambias el tamaño de la ventana.

**Causa:** Bug en el IntersectionObserver del tema default de Bagisto. No detecta elementos que ya están en viewport al momento del mount.

**Solución temporal:** Recargar la página con Ctrl+F5.

**Solución permanente:** Crear theme package propio con el fix del lazy loading. (Pendiente)

---

## Cache

### Cambios en vistas no se reflejan

**Síntomas:** Editaste un archivo `.blade.php` pero los cambios no se ven.

**Solución:**
```bash
docker exec ramplaza-app php artisan view:clear
docker exec ramplaza-app php artisan cache:clear
```

### Cambios en configuración no se reflejan

**Síntomas:** Cambiaste algo en `.env` o `config/` pero no funciona.

**Solución:**
```bash
docker exec ramplaza-app php artisan config:clear
# Si usas config:cache en producción:
docker exec ramplaza-app php artisan config:cache
```

### Error "Configuration not cached"

**Síntomas:** Error relacionado con cache de configuración.

**Solución:**
```bash
docker exec ramplaza-app php artisan optimize:clear
docker exec ramplaza-app php artisan optimize
```

---

## Assets (CSS/JS)

### Estilos CSS no se aplican

**Síntomas:** Los cambios en CSS no se ven en el navegador.

**Causas posibles:**
1. **Cache del navegador** - Hard refresh con Ctrl+F5
2. **Assets no compilados** - Recompilar:
```bash
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm run build"
```
3. **manifest.json desactualizado** - El hash de los archivos cambió

### Error "Vite manifest not found"

**Síntomas:** Error 500 o página en blanco con error de Vite.

**Solución:**
```bash
docker exec ramplaza-app bash -c "cd /var/www/html && npm install && npm run build"
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm install && npm run build"
```

### npm command not found

**Síntomas:** Al intentar compilar assets, npm no está disponible.

**Solución:** npm debe ejecutarse dentro del contenedor:
```bash
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm install && npm run build"
```

---

## Base de Datos

### Error "Connection refused" a MySQL

**Síntomas:** La aplicación no puede conectar a la base de datos.

**Verificar:**
1. Contenedor MySQL corriendo:
```bash
docker ps | grep ramplaza-mysql
```

2. Logs de MySQL:
```bash
docker logs ramplaza-mysql --tail 50
```

3. Variables de entorno correctas en `.env`:
```bash
DB_HOST=ramplaza-mysql  # Nombre del contenedor, no localhost
```

### Migración fallida

**Síntomas:** `php artisan migrate` falla.

**Solución:**
1. Ver el error específico
2. Si es problema de foreign key:
```bash
docker exec ramplaza-app php artisan migrate:fresh --seed  # CUIDADO: borra datos
```

3. Si es problema de permisos de BD:
```bash
docker exec -it ramplaza-mysql mysql -u root -p
GRANT ALL PRIVILEGES ON ram_plaza.* TO 'ramplaza'@'%';
FLUSH PRIVILEGES;
```

---

## Docker

### Contenedor no inicia

**Síntomas:** `docker ps` no muestra el contenedor.

**Diagnóstico:**
```bash
docker ps -a | grep ramplaza  # Ver todos, incluso detenidos
docker logs ramplaza-app      # Ver por qué falló
```

**Soluciones comunes:**
1. Puerto ocupado - Cambiar puerto en docker-compose
2. Volumen corrupto - `docker volume rm` y recrear
3. Imagen corrupta - `docker compose build --no-cache`

### Error "network not found"

**Síntomas:** Error de red al iniciar contenedores.

**Solución:**
```bash
docker network create wowonder_shared
docker compose -f docker-compose.prod.yml up -d
```

### Espacio en disco lleno

**Síntomas:** Errores de escritura, contenedores fallando.

**Solución:**
```bash
# Ver uso
docker system df

# Limpiar
docker system prune -f
docker image prune -a -f  # Elimina imágenes no usadas
```

---

## Nginx

### Error 502 Bad Gateway

**Síntomas:** El sitio muestra error 502.

**Causas:**
1. **Contenedor app no corriendo:**
```bash
docker ps | grep ramplaza-app
docker restart ramplaza-app
```

2. **PHP-FPM no responde:**
```bash
docker logs ramplaza-app --tail 50
```

3. **Timeout en operaciones largas:**
   - Aumentar `fastcgi_read_timeout` en nginx config

### Error 413 Request Entity Too Large

**Síntomas:** No puedes subir archivos grandes.

**Solución:** Aumentar `client_max_body_size` en nginx:
```nginx
client_max_body_size 100M;
```

Luego:
```bash
sudo nginx -t && sudo systemctl reload nginx
```

---

## Elasticsearch

### Búsqueda no funciona

**Síntomas:** La búsqueda de productos no devuelve resultados.

**Solución:**
```bash
# Verificar que Elasticsearch está corriendo
docker ps | grep ramplaza-elasticsearch

# Reindexar
docker exec ramplaza-app php artisan scout:flush "Webkul\Product\Models\Product"
docker exec ramplaza-app php artisan scout:import "Webkul\Product\Models\Product"
```

### Elasticsearch no inicia (memoria)

**Síntomas:** Contenedor de Elasticsearch se reinicia constantemente.

**Solución:** Ajustar memoria en docker-compose:
```yaml
elasticsearch:
  environment:
    - "ES_JAVA_OPTS=-Xms512m -Xmx1g"
```

---

## Queue/Jobs

### Emails no se envían

**Síntomas:** Los correos (confirmación de orden, etc.) no llegan.

**Verificar:**
1. Queue worker corriendo:
```bash
docker ps | grep ramplaza-queue
docker logs ramplaza-queue --tail 50
```

2. Configuración de mail en `.env`

3. Jobs fallidos:
```bash
docker exec ramplaza-app php artisan queue:failed
```

### Jobs se acumulan

**Síntomas:** Los jobs no se procesan.

**Solución:**
```bash
docker restart ramplaza-queue
# O procesar manualmente:
docker exec ramplaza-app php artisan queue:work redis --timeout=300
```

---

## SSL/HTTPS

### Certificado expirado

**Síntomas:** Navegador muestra advertencia de certificado.

**Solución:**
```bash
sudo certbot renew
sudo systemctl reload nginx
```

### Mixed content warnings

**Síntomas:** Algunos recursos cargan por HTTP en lugar de HTTPS.

**Solución:** Verificar `APP_URL` en `.env`:
```
APP_URL=https://plaza.redactivamexico.net
```

---

## Performance

### Sitio lento

**Diagnóstico:**
```bash
# Ver uso de recursos
docker stats --no-stream | grep ramplaza

# Ver queries lentas
docker exec ramplaza-app php artisan telescope:prune  # Si usas Telescope
```

**Soluciones:**
1. Optimizar cache:
```bash
docker exec ramplaza-app php artisan optimize
```

2. Verificar Redis:
```bash
docker exec ramplaza-redis redis-cli ping
```

3. Verificar índices de Elasticsearch

---

## Permisos

### Error "Permission denied" en storage

**Síntomas:** Errores de escritura en logs o uploads.

**Solución:**
```bash
docker exec ramplaza-app chmod -R 775 storage bootstrap/cache
docker exec ramplaza-app chown -R www-data:www-data storage bootstrap/cache
```

---

## Git

### Archivos compilados con conflictos

**Síntomas:** Conflictos en `public/themes/*/build/` después de pull.

**Solución:** Siempre commitear assets compilados junto con fuentes:
```bash
git checkout --theirs public/themes/  # Tomar versión remota
# O recompilar localmente
```

Ver más en: [CLAUDE.md](/CLAUDE.md)
