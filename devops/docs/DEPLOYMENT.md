# Deployment a Producción - RAM Plaza

## Arquitectura del Servidor

```
┌────────────────────────────────────────────────────────┐
│                 Internet (80/443)                       │
└────────────────────┬───────────────────────────────────┘
                     │
        ┌────────────▼────────────┐
        │   Nginx (Host Server)   │
        │  redactivamexico.net    │
        └────────┬────────┬───────┘
                 │        │
    ┌────────────┘        └────────────┐
    │                                  │
┌───▼──────────────────┐   ┌──────────▼──────────────┐
│   WoWonder Docker    │   │   Bagisto Docker        │
│   (Existente)        │   │   (Nuevo)               │
├──────────────────────┤   ├─────────────────────────┤
│ - App Container      │   │ - App Container         │
│ - MySQL Container    │   │ - Webserver (Nginx)     │
│ - Redis Container    │   │ - MySQL Container       │
│ - Otros servicios    │   │ - Redis Container       │
│                      │   │ - Elasticsearch         │
│ Puerto: ?            │   │ Puerto: 8080            │
└──────────────────────┘   └─────────────────────────┘
         │                              │
         └──────────┬───────────────────┘
                    │
         ┌──────────▼──────────┐
         │  Red Compartida     │
         │  wowonder_shared    │
         │  (Para SSO/API)     │
         └─────────────────────┘
```

## Configuración Paso a Paso

### 1. Preparar Red Compartida para Comunicación

Necesitamos una red Docker para que WoWonder y Bagisto puedan comunicarse internamente (para SSO).

```bash
# En el servidor de producción, crear la red compartida
docker network create wowonder_shared
```

### 2. Conectar WoWonder a la Red Compartida

Necesitas agregar esta red al docker-compose.yml de WoWonder (si no la tiene ya).

**Editar docker-compose.yml de WoWonder** (ubicación según tu setup):

```yaml
services:
  wowonder-app:  # Ajusta el nombre según tu contenedor
    # ... configuración existente ...
    networks:
      - default  # Red por defecto de WoWonder
      - wowonder_shared  # Nueva red compartida

networks:
  default:
    driver: bridge
  wowonder_shared:
    external: true
```

Luego reiniciar WoWonder:
```bash
cd /ruta/a/wowonder
docker compose down
docker compose up -d
```

### 3. Estructura de Directorios en el Servidor

```
/home/tu-usuario/
├── wowonder/               # Proyecto WoWonder existente
│   ├── docker-compose.yml
│   └── ... (archivos de WoWonder)
│
└── ramplaza/           # Proyecto Bagisto nuevo
    ├── .env.production
    ├── docker-compose.production.yml
    ├── docker/
    │   ├── Dockerfile.production
    │   └── nginx/
    │       └── production.conf
    └── ... (código de Bagisto)
```

### 4. Clonar Proyecto en el Servidor

```bash
# SSH al servidor
ssh usuario@redactivamexico.net

# Clonar repositorio
cd ~
git clone https://github.com/JuanLalo/RamPlaza.git ramplaza
cd ramplaza

# Usar branch main (o dev según prefieras)
git checkout main
```

### 5. Configurar Variables de Entorno

```bash
cd ~/ramplaza
cp .env.example .env.production
nano .env.production
```

**Configuración en .env.production:**

```env
APP_NAME="RAM Plaza"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://plaza.redactivamexico.net
APP_ADMIN_URL=admin
APP_TIMEZONE=America/Mexico_City

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_MX
APP_CURRENCY=MXN

# Base de datos (contenedor propio de Bagisto)
DB_CONNECTION=mysql
DB_HOST=ramplaza-mysql
DB_PORT=3306
DB_DATABASE=ram_plaza
DB_USERNAME=ramplaza
DB_PASSWORD=<GENERAR_PASSWORD_FUERTE>

# Cache y sesiones (Redis propio de Bagisto)
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=ramplaza-redis
REDIS_PORT=6379

# WoWonder SSO - URL INTERNA (nombre del contenedor de WoWonder)
WOWONDER_API_URL=http://wowonder-app/api
# Ajusta "wowonder-app" al nombre real de tu contenedor de WoWonder
# Para encontrarlo: docker ps | grep wowonder

WOWONDER_SERVER_KEY=<TU_SERVER_KEY_DE_PRODUCCION>

# Mail
MAIL_MAILER=smtp
MAIL_HOST=<tu-servidor-smtp>
MAIL_PORT=587
MAIL_USERNAME=<usuario-smtp>
MAIL_PASSWORD=<password-smtp>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=comercios@redactivamexico.net
MAIL_FROM_NAME="RAM Plaza"

# Elasticsearch
SCOUT_DRIVER=elasticsearch
ELASTICSEARCH_HOST=ramplaza-elasticsearch
ELASTICSEARCH_PORT=9200

# Stripe (Production)
STRIPE_KEY=pk_live_xxxxxxxxxxxxx
STRIPE_SECRET=sk_live_xxxxxxxxxxxxx

# Configuración adicional
RESPONSE_CACHE_ENABLED=true
LOG_CHANNEL=daily
LOG_LEVEL=error
```

### 6. Crear Dockerfile de Producción

**Archivo: `docker/Dockerfile.production`**

```dockerfile
FROM php:8.3-fpm

# Argumentos de build
ARG WWWGROUP=1000
ARG NODE_VERSION=20

# Variables de entorno
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=America/Mexico_City

# Configurar timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    calendar

# Instalar Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - \
    && apt-get install -y nodejs

# Crear usuario para la aplicación
RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1000 sail

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de la aplicación
COPY --chown=sail:sail . /var/www/html

# Instalar dependencias de Composer (sin dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar permisos
RUN chown -R sail:sail /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Exponer puerto
EXPOSE 9000

# Comando por defecto
CMD ["php-fpm"]
```

### 7. Crear Configuración de Nginx (para contenedor interno)

**Archivo: `docker/nginx/production.conf`**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name _;
    root /var/www/html/public;

    index index.php index.html index.htm;

    charset utf-8;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Logging
    access_log /var/log/nginx/bagisto-access.log;
    error_log /var/log/nginx/bagisto-error.log;

    # Disable access to hidden files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Main location block
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass ramplaza-app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;

        # Increase timeouts for admin operations
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
    }

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ /\.(env|git|svn) {
        deny all;
        return 404;
    }

    # Client body size (para uploads de productos)
    client_max_body_size 100M;
}
```

### 8. Crear docker-compose.production.yml

**Archivo: `docker-compose.production.yml`**

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: ./docker/Dockerfile.production
    container_name: ramplaza-app
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html
      - ./storage/app/public:/var/www/html/public/storage
    env_file:
      - .env.production
    networks:
      - ramplaza
      - wowonder_shared
    depends_on:
      - mysql
      - redis
      - elasticsearch

  webserver:
    image: nginx:alpine
    container_name: ramplaza-webserver
    restart: unless-stopped
    ports:
      - "127.0.0.1:8080:80"
    volumes:
      - ./:/var/www/html
      - ./docker/nginx/production.conf:/etc/nginx/conf.d/default.conf
      - ./storage/logs/nginx:/var/log/nginx
    networks:
      - ramplaza
    depends_on:
      - app

  mysql:
    image: mysql:8.0
    container_name: ramplaza-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ${DB_DATABASE:-ram_plaza}
      MYSQL_USER: ${DB_USERNAME:-ramplaza}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
    volumes:
      - mysql-data:/var/lib/mysql
    command: --default-authentication-plugin=mysql_native_password
    networks:
      - ramplaza
    ports:
      - "127.0.0.1:3308:3306"

  redis:
    image: redis:alpine
    container_name: ramplaza-redis
    restart: unless-stopped
    command: redis-server --appendonly yes --requirepass ${REDIS_PASSWORD:-null}
    volumes:
      - redis-data:/data
    networks:
      - ramplaza
      - wowonder_shared

  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:7.17.0
    container_name: ramplaza-elasticsearch
    restart: unless-stopped
    environment:
      - discovery.type=single-node
      - "ES_JAVA_OPTS=-Xms512m -Xmx1g"
      - xpack.security.enabled=false
    volumes:
      - elasticsearch-data:/usr/share/elasticsearch/data
    networks:
      - ramplaza
    ulimits:
      memlock:
        soft: -1
        hard: -1
      nofile:
        soft: 65536
        hard: 65536

  queue-worker:
    build:
      context: .
      dockerfile: ./docker/Dockerfile.production
    container_name: ramplaza-queue
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html
    env_file:
      - .env.production
    command: php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --timeout=300
    networks:
      - ramplaza
      - wowonder_shared
    depends_on:
      - mysql
      - redis

  scheduler:
    build:
      context: .
      dockerfile: ./docker/Dockerfile.production
    container_name: ramplaza-scheduler
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html
    env_file:
      - .env.production
    command: sh -c "while true; do php artisan schedule:run --verbose --no-interaction & sleep 60; done"
    networks:
      - ramplaza
    depends_on:
      - mysql
      - redis

volumes:
  mysql-data:
    driver: local
  redis-data:
    driver: local
  elasticsearch-data:
    driver: local

networks:
  ramplaza:
    driver: bridge
  wowonder_shared:
    external: true
```

### 9. Configurar Nginx en el Host

Crear archivo de configuración para el sitio de Bagisto.

**Archivo en el servidor: `/etc/nginx/sites-available/plaza.redactivamexico.net`**

```nginx
# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name plaza.redactivamexico.net;

    # Certbot challenge
    location /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }

    location / {
        return 301 https://$server_name$request_uri;
    }
}

# HTTPS Server
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name plaza.redactivamexico.net;

    # SSL Certificates (ajustar rutas según tu configuración)
    ssl_certificate /etc/letsencrypt/live/plaza.redactivamexico.net/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/plaza.redactivamexico.net/privkey.pem;

    # SSL Configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Logging
    access_log /var/log/nginx/comercios-access.log;
    error_log /var/log/nginx/comercios-error.log;

    # Proxy to Docker container
    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Port $server_port;

        # Timeouts
        proxy_connect_timeout 300;
        proxy_send_timeout 300;
        proxy_read_timeout 300;
        send_timeout 300;

        # WebSocket support (si usas Pusher/Broadcasting)
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }

    # Max upload size
    client_max_body_size 100M;
}
```

### 10. Activar Configuración de Nginx

```bash
# Crear symlink para habilitar el sitio
sudo ln -s /etc/nginx/sites-available/plaza.redactivamexico.net /etc/nginx/sites-enabled/

# Verificar configuración
sudo nginx -t

# Si todo está bien, NO recargar aún (falta SSL)
```

### 11. Obtener Certificado SSL con Let's Encrypt

```bash
# Instalar certbot si no lo tienes
sudo apt-get update
sudo apt-get install certbot python3-certbot-nginx

# Obtener certificado
sudo certbot --nginx -d plaza.redactivamexico.net

# Verificar renovación automática
sudo certbot renew --dry-run
```

### 12. Deploy de Bagisto

```bash
cd ~/ramplaza

# 1. Instalar dependencias (usando Docker temporal)
docker run --rm \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --no-dev --optimize-autoloader

# 2. Generar APP_KEY
docker compose -f docker-compose.production.yml run --rm app php artisan key:generate --force --env=production

# La clave se guardará en .env.production

# 3. Build de assets de producción
docker compose -f docker-compose.production.yml run --rm app npm ci
docker compose -f docker-compose.production.yml run --rm app npm run build

# 4. Levantar todos los servicios
docker compose -f docker-compose.production.yml up -d

# 5. Esperar a que MySQL esté listo
sleep 10

# 6. Ejecutar migraciones
docker compose -f docker-compose.production.yml exec app php artisan migrate --force

# 7. Ejecutar seeders (solo primera vez)
docker compose -f docker-compose.production.yml exec app php artisan db:seed --force

# 8. Optimizar para producción
docker compose -f docker-compose.production.yml exec app php artisan config:cache
docker compose -f docker-compose.production.yml exec app php artisan route:cache
docker compose -f docker-compose.production.yml exec app php artisan view:cache
docker compose -f docker-compose.production.yml exec app php artisan optimize

# 9. Crear symlink de storage
docker compose -f docker-compose.production.yml exec app php artisan storage:link

# 10. Configurar permisos
docker compose -f docker-compose.production.yml exec app chown -R www-data:www-data storage bootstrap/cache
docker compose -f docker-compose.production.yml exec app chmod -R 775 storage bootstrap/cache
```

### 13. Recargar Nginx

```bash
# Recargar Nginx para aplicar la nueva configuración
sudo systemctl reload nginx

# O
sudo nginx -s reload
```

### 14. Verificar Instalación

```bash
# Ver logs de los contenedores
docker compose -f docker-compose.production.yml logs -f

# Ver estado de los servicios
docker compose -f docker-compose.production.yml ps

# Verificar conectividad con WoWonder
docker exec ramplaza-app ping wowonder-app -c 3

# Acceder a la aplicación
# https://plaza.redactivamexico.net
```

## Verificación del SSO

Para verificar que Bagisto puede comunicarse con WoWonder:

```bash
# Desde el contenedor de Bagisto, hacer curl al API de WoWonder
docker exec ramplaza-app curl http://wowonder-app/api/get-user-data

# Debería responder (aunque sea error de autenticación, significa que la conexión funciona)
```

## Comandos Útiles de Mantenimiento

### Actualizar Código

```bash
cd ~/ramplaza
git pull origin main

# Rebuild de contenedores
docker compose -f docker-compose.production.yml down
docker compose -f docker-compose.production.yml up -d --build

# Ejecutar migraciones si hay nuevas
docker compose -f docker-compose.production.yml exec app php artisan migrate --force

# Limpiar caches
docker compose -f docker-compose.production.yml exec app php artisan cache:clear
docker compose -f docker-compose.production.yml exec app php artisan config:cache
docker compose -f docker-compose.production.yml exec app php artisan route:cache
docker compose -f docker-compose.production.yml exec app php artisan view:cache
```

### Backups

```bash
# Backup de base de datos
docker exec ramplaza-mysql mysqldump -u ramplaza -p ram_plaza > backup-$(date +%Y%m%d).sql

# Backup de archivos subidos
tar -czf storage-backup-$(date +%Y%m%d).tar.gz ~/ramplaza/storage/app
```

### Ver Logs

```bash
# Logs de la aplicación
docker compose -f docker-compose.production.yml logs -f app

# Logs de Nginx (contenedor)
docker compose -f docker-compose.production.yml logs -f webserver

# Logs de Nginx (host)
sudo tail -f /var/log/nginx/comercios-access.log
sudo tail -f /var/log/nginx/comercios-error.log

# Logs de Laravel
docker compose -f docker-compose.production.yml exec app tail -f storage/logs/laravel.log
```

## Troubleshooting

### Error: Cannot connect to WoWonder

Verificar que ambos proyectos estén en la red compartida:

```bash
# Ver redes de WoWonder
docker inspect <wowonder-container-id> | grep Networks -A 10

# Ver redes de Bagisto
docker inspect ramplaza-app | grep Networks -A 10

# Deben compartir "wowonder_shared"
```

### Error 502 Bad Gateway en Nginx

```bash
# Verificar que el contenedor webserver esté corriendo
docker ps | grep ramplaza-webserver

# Verificar logs
docker compose -f docker-compose.production.yml logs webserver
```

### Performance Issues

```bash
# Aumentar memoria de Elasticsearch en docker-compose.production.yml
# ES_JAVA_OPTS=-Xms1g -Xmx2g

# Verificar uso de recursos
docker stats
```

## Próximos Pasos

1. **Implementar SSO con WoWonder** - Crear middleware y controlador
2. **Sistema de cupones físicos** - Desarrollar package custom
3. **Multi-vendor marketplace** - Evaluar extensión de Bagisto
4. **Configurar backups automáticos** - Cron jobs
5. **Monitoring** - Configurar herramientas como Uptime Kuma o similar
