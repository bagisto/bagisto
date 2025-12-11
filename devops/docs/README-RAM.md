# RAM Comercios - E-commerce Platform

Plataforma de e-commerce basada en Bagisto para Red Activa México.

## Stack Tecnológico

- **Framework**: Laravel 11 + Bagisto 2.3
- **Frontend**: Vue 3 + Tailwind CSS
- **Base de datos**: MySQL 8.0
- **Cache**: Redis
- **Búsqueda**: Elasticsearch 7.17
- **Mail**: Mailpit (desarrollo)
- **Container**: Docker + Laravel Sail

## Requisitos

- Docker Desktop (Windows/Mac) o Docker Engine (Linux)
- Git
- 4GB RAM mínimo
- 10GB espacio en disco

## Instalación Local

### 1. Clonar el repositorio

```bash
git clone https://github.com/JuanLalo/RamComercios.git
cd RamComercios
```

### 2. Configurar variables de entorno

```bash
cp .env.example .env
```

El .env ya está configurado para desarrollo local con:
- Locale: Español (es)
- Moneda: MXN
- Timezone: America/Mexico_City

### 3. Instalar dependencias

```bash
docker run --rm \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Levantar servicios

```bash
docker compose up -d
```

**Primera vez**: La construcción de la imagen Docker toma ~5-10 minutos.

### 5. Configurar base de datos

```bash
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate:fresh --seed
```

### 6. Acceder a la aplicación

- **Tienda**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
  - Email: `admin@example.com`
  - Password: `admin123`

## Servicios Disponibles

| Servicio | Puerto | URL |
|----------|--------|-----|
| Aplicación | 8000 | http://localhost:8000 |
| MySQL | 3307 | localhost:3307 |
| Redis | 6380 | localhost:6380 |
| Mailpit UI | 8025 | http://localhost:8025 |
| Elasticsearch | 9200 | http://localhost:9200 |
| Kibana | 5601 | http://localhost:5601 |
| Vite (dev) | 5173 | http://localhost:5173 |

## Comandos Útiles

### Docker Compose

```bash
# Levantar servicios
docker compose up -d

# Detener servicios
docker compose down

# Ver logs
docker compose logs -f laravel.test

# Reiniciar servicio
docker compose restart laravel.test

# Ver estado de contenedores
docker compose ps
```

### Artisan

```bash
# Ejecutar comando artisan
docker compose exec laravel.test php artisan <comando>

# Ejemplos:
docker compose exec laravel.test php artisan config:clear
docker compose exec laravel.test php artisan cache:clear
docker compose exec laravel.test php artisan route:list
docker compose exec laravel.test php artisan migrate
```

### Composer

```bash
docker compose exec laravel.test composer <comando>

# Ejemplos:
docker compose exec laravel.test composer require laravel/sanctum
docker compose exec laravel.test composer update
```

### NPM

```bash
docker compose exec laravel.test npm <comando>

# Ejemplos:
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run dev
docker compose exec laravel.test npm run build
```

### Base de datos

```bash
# Conectar a MySQL
docker compose exec mysql mysql -u sail -ppassword ram_comercios

# Backup
docker compose exec mysql mysqldump -u sail -ppassword ram_comercios > backup.sql

# Restore
cat backup.sql | docker compose exec -T mysql mysql -u sail -ppassword ram_comercios
```

## Estrategia Git

### Remotes

- `origin` → https://github.com/JuanLalo/RamComercios.git (tu fork)
- `upstream` → https://github.com/bagisto/bagisto.git (Bagisto oficial)

### Actualizar desde upstream (Bagisto oficial)

```bash
# Traer cambios de Bagisto oficial
git fetch upstream

# Ver cambios
git log HEAD..upstream/master --oneline

# Merge cambios (en branch dev)
git checkout dev
git merge upstream/master

# Resolver conflictos si los hay
# Editar archivos conflictivos
git add .
git commit

# Push cambios
git push origin dev
```

### Branches

- `main` → Producción (protegida)
- `dev` → Desarrollo activo

### Workflow

```bash
# 1. Crear rama para feature
git checkout -b feature/nombre-feature dev

# 2. Hacer cambios y commits
git add .
git commit -m "feat: descripción del cambio"

# 3. Push y PR
git push origin feature/nombre-feature
# Crear Pull Request a 'dev' en GitHub

# 4. Después de merge, actualizar dev local
git checkout dev
git pull origin dev
```

## Estructura del Proyecto

```
RamComercios/
├── app/                    # Código de aplicación Laravel
├── packages/Webkul/        # Packages de Bagisto
│   ├── Admin/             # Panel administrativo
│   ├── Shop/              # Tienda (frontend)
│   ├── Core/              # Núcleo de Bagisto
│   ├── Customer/          # Gestión de clientes
│   ├── Product/           # Gestión de productos
│   └── ...                # Otros módulos
├── database/
│   ├── migrations/        # Migraciones de BD
│   └── seeders/          # Datos iniciales
├── public/               # Assets públicos
├── resources/
│   ├── views/           # Vistas Blade
│   ├── js/              # JavaScript/Vue
│   └── css/             # Estilos
├── docker-compose.yml   # Configuración Docker
├── .env                 # Variables de entorno (no versionado)
├── .env.example         # Plantilla de .env
├── README.md            # Documentación original de Bagisto
└── README-RAM.md        # Esta documentación
```

## Customizaciones Planificadas

### 1. SSO con WoWonder

Integración de Single Sign-On para que usuarios de redactivamexico.net puedan acceder automáticamente.

**Flujo**:
1. Usuario logueado en RAM → click "Comercios"
2. RAM genera URL: `comercios.redactivamexico.net/sso?token={access_token}`
3. Bagisto valida token contra WoWonder API
4. Crea/actualiza customer en Bagisto
5. Usuario queda logueado

**Endpoint WoWonder**:
```
POST /api/get-user-data
Body: { "server_key": "XXX", "access_token": "YYY" }
Response: { "api_status": 200, "user_data": { ... } }
```

**Implementación**:
- Middleware: `app/Http/Middleware/WoWonderSSO.php`
- Config: `config/wowonder.php`

### 2. Sistema de Cupones Físicos

Generación y validación de cupones con QR para uso en tiendas físicas.

**Funcionalidades**:
- Generar cupones con código QR
- API para que negocios validen cupones en tienda física
- Registro de canjes
- Dashboard para vendedores

**Implementación**:
- Package custom: `packages/Webkul/PhysicalCoupons/`

### 3. Vinculación con Vendedores de WoWonder

Negocios de RAM (cuentas WoWonder) = Vendors en Bagisto

**Campo adicional**: `wowonder_user_id` en tabla vendors

### 4. Multi-Vendor Marketplace (futuro)

Extensión oficial de Bagisto (~$499 USD):
- Gestión de múltiples vendedores
- Comisiones por venta
- Dashboard para vendors
- Aprobación de productos

### 5. Stripe Connect (futuro)

Extensión para split payments (~$99 USD):
- Pagos automáticos a vendedores
- Retención de comisiones
- Transferencias programadas

## Deployment a Producción

### Servidor AWS

**Especificaciones recomendadas**:
- EC2: t3.medium (2 vCPU, 4GB RAM)
- RDS: MySQL 8.0 (db.t3.small)
- ElastiCache: Redis (cache.t3.micro)
- S3: Almacenamiento de uploads
- CloudFront: CDN para assets

### Preparación

```bash
# 1. Configurar .env.production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://comercios.redactivamexico.net

DB_HOST=ram-db.xxx.rds.amazonaws.com
DB_DATABASE=ram_comercios
DB_USERNAME=ram_comercios
DB_PASSWORD=<strong_password>

REDIS_HOST=ram-redis.xxx.cache.amazonaws.com

# WoWonder Production
WOWONDER_API_URL=https://redactivamexico.net
WOWONDER_SERVER_KEY=<production_key>

# Stripe Live
STRIPE_KEY=pk_live_xxx
STRIPE_SECRET=sk_live_xxx

# 2. Build assets
npm run build

# 3. Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Variables de Entorno Clave

**Desarrollo (.env.local)**:
```env
APP_NAME="RAM Comercios"
APP_ENV=local
APP_URL=http://localhost:8000
APP_LOCALE=es
APP_CURRENCY=MXN
APP_TIMEZONE=America/Mexico_City

DB_HOST=mysql
DB_DATABASE=ram_comercios_local
DB_USERNAME=sail
DB_PASSWORD=password

WOWONDER_API_URL=https://redactivamexico.net
WOWONDER_SERVER_KEY=xxx

MAIL_HOST=mailpit
MAIL_PORT=1025
```

**Producción (.env.production)**:
```env
APP_NAME="RAM Comercios"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://comercios.redactivamexico.net

DB_HOST=<rds-endpoint>
DB_DATABASE=ram_comercios
DB_USERNAME=ram_comercios
DB_PASSWORD=<strong-password>

REDIS_HOST=<elasticache-endpoint>

MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=xxx
AWS_SECRET_ACCESS_KEY=xxx

WOWONDER_API_URL=https://redactivamexico.net
WOWONDER_SERVER_KEY=<production-key>

STRIPE_KEY=pk_live_xxx
STRIPE_SECRET=sk_live_xxx
```

## Troubleshooting

### Puerto 8000 ocupado

Cambiar `APP_PORT` en `.env`:
```env
APP_PORT=8001
```

Luego:
```bash
docker compose down
docker compose up -d
```

### Permisos de archivos

```bash
docker compose exec laravel.test chmod -R 775 storage bootstrap/cache
docker compose exec laravel.test chown -R sail:sail storage bootstrap/cache
```

### Limpiar caché

```bash
docker compose exec laravel.test php artisan cache:clear
docker compose exec laravel.test php artisan config:clear
docker compose exec laravel.test php artisan view:clear
docker compose exec laravel.test php artisan route:clear
```

### Reconstruir contenedores

```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

### Error: "No application encryption key has been specified"

```bash
docker compose exec laravel.test php artisan key:generate
```

### MySQL "Connection refused"

Esperar a que MySQL termine de inicializar:
```bash
docker compose logs mysql
# Esperar mensaje: "ready for connections"
```

### Elasticsearch no inicia

Puede necesitar más memoria. Editar `docker-compose.yml`:
```yaml
elasticsearch:
  environment:
    - "ES_JAVA_OPTS=-Xms512m -Xmx512m"  # Aumentar si es necesario
```

## Recursos

- **Documentación Bagisto**: https://devdocs.bagisto.com/
- **Laravel Docs**: https://laravel.com/docs/11.x
- **Docker Compose**: https://docs.docker.com/compose/
- **Bagisto Forums**: https://forums.bagisto.com/
- **Bagisto Extensions**: https://bagisto.com/en/extensions/

## Comandos de Desarrollo

### Compilar assets

```bash
# Modo desarrollo (watch)
docker compose exec laravel.test npm run dev

# Build producción
docker compose exec laravel.test npm run build
```

### Tests

```bash
# Ejecutar tests
docker compose exec laravel.test php artisan test

# Con coverage
docker compose exec laravel.test php artisan test --coverage
```

### Debugging

PHPDebugBar está activado en desarrollo. Visible en la parte inferior de cada página.

## Soporte

Para dudas o problemas:
- Crear issue en GitHub
- Consultar documentación de Bagisto
- Contactar al equipo de desarrollo

---

**Desarrollado para Red Activa México** 🇲🇽
