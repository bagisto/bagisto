# DevOps - RAM Plaza

Configuración y documentación para deployment de RAM Plaza (Bagisto) en producción.

## 📁 Estructura

```
devops/
├── README.md                          # Este archivo
├── docs/
│   ├── README-RAM.md                  # Documentación del proyecto
│   ├── DEPLOYMENT.md                  # Guía completa de deployment
│   └── GIT-STRATEGY.md                # Estrategia de Git y ramas
├── docker/
│   ├── Dockerfile.production          # Dockerfile de producción
│   ├── .env.production.example        # Template de variables de entorno
│   └── nginx/
│       └── production.conf            # Config de Nginx interno
├── nginx/
│   └── comercios.redactiva.conf       # Config de Nginx del host
└── scripts/
    ├── deploy.sh                      # Script de deployment
    ├── backup.sh                      # Script de backup
    └── setup-network.sh               # Setup de red compartida

Root (Docker Compose):
├── docker-compose.yml                 # Local/Dev (Laravel Sail)
└── docker-compose.prod.yml            # Production
```

## 🚀 Quick Start

### Desarrollo Local

Ya está configurado con Laravel Sail. Ver [README.md](../README.md) en la raíz.

```bash
docker compose up -d
```

### Deployment a Producción

Ver documentación completa en [DEPLOYMENT.md](docs/DEPLOYMENT.md).

```bash
# 1. Clonar en servidor
git clone https://github.com/JuanLalo/RamPlaza.git ~/apps/RamPlaza
cd ~/apps/RamPlaza

# 2. Configurar variables
cp devops/docker/.env.production.example devops/docker/.env.production
nano devops/docker/.env.production

# 3. Ejecutar deployment
./devops/scripts/deploy.sh
```

## 📚 Documentación

- **[README-RAM.md](docs/README-RAM.md)** - Documentación completa del proyecto
- **[DEPLOYMENT.md](docs/DEPLOYMENT.md)** - Guía de deployment en servidor con WoWonder
- **[GIT-STRATEGY.md](docs/GIT-STRATEGY.md)** - Estrategia de ramas y workflow
- **[SOCIAL-COMMERCE-PLAN.md](docs/SOCIAL-COMMERCE-PLAN.md)** - 🆕 Plan de "Muro Loco" - Feed social de productos

## 🌿 Estrategia de Ramas (GitHub Flow)

- `2.3` - Sincronizada con Bagisto upstream (NO modificar directamente)
- `main` - Rama de producción + desarrollo
- `feature/*` - Ramas de features temporales
- `fix/*` - Ramas de fixes temporales

Ver [GIT-STRATEGY.md](docs/GIT-STRATEGY.md) para detalles completos.

## 🔧 Herramientas

- **Docker Compose** - Orquestación de contenedores
- **Nginx** - Reverse proxy y SSL
- **Let's Encrypt** - Certificados SSL
- **GitHub Actions** - CI/CD (futuro)

## 📞 Soporte

Para dudas sobre deployment, consultar la documentación en `docs/`.
