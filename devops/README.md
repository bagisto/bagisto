# DevOps - RAM Plaza

Documentación de operaciones y mantenimiento para RAM Plaza (Bagisto e-commerce).

**URL Producción:** https://plaza.redactivamexico.net

---

## Estructura

```
devops/
├── README.md                           # Este archivo
│
├── docs/
│   ├── operations/                     # 🔧 Operaciones diarias
│   │   ├── RUNBOOKS.md                 # Procedimientos operacionales
│   │   ├── TROUBLESHOOTING.md          # Problemas y soluciones
│   │   └── MAINTENANCE.md              # Mantenimiento regular
│   │
│   ├── setup/                          # 🚀 Setup inicial
│   │   └── DEPLOYMENT.md               # Guía de deployment
│   │
│   ├── development/                    # 💻 Desarrollo
│   │   └── GIT-STRATEGY.md             # Estrategia Git (GitHub Flow)
│   │
│   └── roadmap/                        # 🗺️ Features futuras
│       ├── SOCIAL-COMMERCE-PLAN.md     # Plan Muro Loco
│       └── MURO-LOCO-RESUMEN.md        # Resumen ejecutivo
│
├── docker/
│   ├── Dockerfile.production           # Dockerfile de producción
│   └── nginx/
│       └── production.conf             # Nginx interno (contenedor)
│
├── nginx/
│   └── plaza.redactiva.conf            # Nginx del host (reverse proxy)
│
├── scripts/
│   ├── deploy.sh                       # Script de deployment
│   ├── backup.sh                       # Script de backup
│   ├── rebuild-assets.sh               # Recompilar CSS/JS y limpiar cache
│   ├── sync-upstream.sh                # Sincronizar con Bagisto
│   └── setup-network.sh                # Setup red Docker
│
└── temp/                               # (gitignored) Notas temporales
```

---

## Quick Reference

### Comandos más usados

```bash
# Cache (IMPORTANTE: siempre incluir responsecache)
docker exec ramplaza-app php artisan optimize:clear
docker exec ramplaza-app php artisan responsecache:clear

# Recompilar assets (CSS/JS)
./devops/scripts/rebuild-assets.sh

# Logs
docker logs ramplaza-app --tail 100 -f

# Estado
docker ps | grep ramplaza

# Reiniciar
docker restart ramplaza-app ramplaza-webserver

# Backup BD
docker exec ramplaza-mysql mysqldump -u ramplaza -p$DB_PASSWORD ram_plaza > backup.sql
```

### Documentación rápida

| Necesito... | Ver documento |
|-------------|---------------|
| Limpiar cache, reiniciar servicios | [RUNBOOKS.md](docs/operations/RUNBOOKS.md) |
| Solucionar un error | [TROUBLESHOOTING.md](docs/operations/TROUBLESHOOTING.md) |
| Hacer backup, actualizar | [MAINTENANCE.md](docs/operations/MAINTENANCE.md) |
| Hacer deploy inicial | [DEPLOYMENT.md](docs/setup/DEPLOYMENT.md) |
| Workflow de Git | [GIT-STRATEGY.md](docs/development/GIT-STRATEGY.md) |

---

## Arquitectura

```
                    Internet
                       │
                       ▼
              ┌────────────────┐
              │  Nginx (Host)  │ :443
              │  SSL/Reverse   │
              └───────┬────────┘
                      │
          ┌───────────▼───────────┐
          │    Docker Network     │
          │    (ramplaza)         │
          └───────────────────────┘
                      │
    ┌─────────────────┼─────────────────┐
    │                 │                 │
    ▼                 ▼                 ▼
┌─────────┐    ┌───────────┐    ┌─────────────┐
│  Nginx  │    │    App    │    │    MySQL    │
│  :8080  │───▶│  PHP-FPM  │───▶│    :3306    │
└─────────┘    └─────┬─────┘    └─────────────┘
                     │
         ┌───────────┼───────────┐
         │           │           │
         ▼           ▼           ▼
    ┌─────────┐ ┌─────────┐ ┌───────────────┐
    │  Redis  │ │  Queue  │ │ Elasticsearch │
    └─────────┘ └─────────┘ └───────────────┘
```

### Contenedores

| Contenedor | Descripción | Puerto |
|------------|-------------|--------|
| ramplaza-app | PHP-FPM + Laravel | 9000 (interno) |
| ramplaza-webserver | Nginx | 8080 → Host |
| ramplaza-mysql | MySQL 8.0 | 3306 (interno) |
| ramplaza-redis | Redis | 6379 (interno) |
| ramplaza-elasticsearch | Elasticsearch 7.17 | 9200 (interno) |
| ramplaza-queue | Queue Worker | - |
| ramplaza-scheduler | Cron Scheduler | - |

---

## Entornos

### Producción
- **URL:** https://plaza.redactivamexico.net
- **Server:** redactivamexico.net
- **Path:** ~/apps/RamPlaza
- **Compose:** docker-compose.prod.yml

### Desarrollo Local
- **URL:** http://localhost:8000
- **Compose:** docker-compose.yml (Laravel Sail)

---

## Reglas de Desarrollo

Ver [CLAUDE.md](/CLAUDE.md) en la raíz del proyecto para:
- Cómo editar código de Bagisto
- Flujo de compilación de assets
- Convenciones de commits
- Qué NO hacer

---

## Contacto

Para problemas o dudas:
1. Revisar [TROUBLESHOOTING.md](docs/operations/TROUBLESHOOTING.md)
2. Consultar [RUNBOOKS.md](docs/operations/RUNBOOKS.md)
3. Revisar logs con comandos de Quick Reference

---

**Última actualización:** 2025-12-30
