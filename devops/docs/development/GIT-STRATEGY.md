# Estrategia de Git - RAM Plaza

## 🎯 Filosofía: GitHub Flow

Usamos **GitHub Flow** - un workflow simple y efectivo:
- Una rama principal: `main`
- Ramas temporales para features y fixes
- Deploy continuo cuando se mergea a `main`

## 🌲 Estructura de Ramas

```
┌─────────────────────────────────────────────────────────┐
│  bagisto/bagisto (upstream)                             │
│  https://github.com/bagisto/bagisto.git                 │
└─────────────────┬───────────────────────────────────────┘
                  │
                  │ fetch/merge
                  ↓
┌─────────────────────────────────────────────────────────┐
│  JuanLalo/RamPlaza (origin)                         │
│  https://github.com/JuanLalo/RamPlaza.git           │
├─────────────────────────────────────────────────────────┤
│  Ramas:                                                 │
│  • 2.3         ← Sincronizada con upstream/2.3         │
│  • main        ← Producción + Desarrollo               │
│  • feature/*   ← Features temporales                   │
│  • fix/*       ← Bug fixes temporales                  │
│  • update/*    ← Updates de Bagisto temporales         │
└─────────────────────────────────────────────────────────┘
```

### Descripción de Ramas

#### `2.3` - Rama de Bagisto Oficial
- **Propósito**: Mantener sincronizada con `bagisto/bagisto@2.3`
- **Regla**: **NUNCA** hacer commits directos aquí
- **Uso**: Solo para recibir updates de Bagisto oficial
- **Merge desde**: `upstream/2.3`
- **Merge hacia**: `main` (cuando hay updates importantes)

#### `main` - Producción y Desarrollo
- **Propósito**: Rama principal del proyecto
- **Contiene**: Código estable + desarrollo activo
- **Regla**: Solo merge via Pull Request
- **Protección**: Branch protegida en GitHub
- **Deploy**: Se deploya automáticamente a producción
- **Tags**: Versiones con tags (v1.0.0, v1.1.0, etc.)
- **Base para**: Todas las ramas de feature/fix

#### `feature/*` - Nuevas Funcionalidades
- **Propósito**: Desarrollo de funcionalidades específicas
- **Nomenclatura**: `feature/nombre-descriptivo`
- **Base**: Siempre desde `main`
- **Merge hacia**: `main` (via Pull Request)
- **Duración**: Temporal (se elimina después del merge)
- **Ejemplos**:
  - `feature/wowonder-sso`
  - `feature/physical-coupons`
  - `feature/vendor-integration`

#### `fix/*` - Bug Fixes
- **Propósito**: Corrección de bugs
- **Nomenclatura**: `fix/descripcion-bug`
- **Base**: Desde `main`
- **Merge hacia**: `main` (via Pull Request)
- **Duración**: Temporal
- **Ejemplos**:
  - `fix/login-redirect`
  - `fix/cart-calculation`
  - `fix/email-validation`

#### `update/*` - Updates de Bagisto
- **Propósito**: Mergear updates de Bagisto upstream
- **Nomenclatura**: `update/bagisto-version`
- **Base**: Desde `main`
- **Uso**: Para probar updates antes de mergear a main
- **Duración**: Temporal
- **Ejemplo**: `update/bagisto-2.3.5`

## 🔧 Configuración Inicial

### 1. Configurar Remotes

```bash
cd ~/repos/RamPlaza

# Verificar remote actual
git remote -v
# origin  https://github.com/JuanLalo/RamPlaza.git (fetch)
# origin  https://github.com/JuanLalo/RamPlaza.git (push)

# Agregar upstream de Bagisto oficial
git remote add upstream https://github.com/bagisto/bagisto.git

# Verificar
git remote -v
# origin    https://github.com/JuanLalo/RamPlaza.git (fetch)
# origin    https://github.com/JuanLalo/RamPlaza.git (push)
# upstream  https://github.com/bagisto/bagisto.git (fetch)
# upstream  https://github.com/bagisto/bagisto.git (push)
```

### 2. Crear Rama Main

```bash
# Crear main desde 2.3 actual
git checkout 2.3
git checkout -b main

# Aplicar customizaciones de RAM
git add devops/
git add .env
git commit -m "feat: initial RAM Plaza setup

- Add devops folder with deployment configs
- Configure for redactivamexico.net (español, MXN)
- Docker production configs
- Nginx configs
- Deployment scripts"

git push -u origin main

# Hacer main la rama por defecto en GitHub
# Settings → Branches → Default branch → main
```

### 3. Proteger Rama Main en GitHub

En GitHub: `Settings → Branches → Add rule`

- Branch name pattern: `main`
- ✅ Require a pull request before merging
- ✅ Require approvals: 1 (opcional si eres solo tú)
- ✅ Dismiss stale pull request approvals
- ✅ Require status checks to pass (si tienes CI/CD)
- ⚠️ No permitir force push

## 📋 Workflows Diarios

### Workflow 1: Desarrollar Nueva Feature

```bash
# 1. Asegurarte de estar en main actualizado
git checkout main
git pull origin main

# 2. Crear rama de feature
git checkout -b feature/wowonder-sso

# 3. Desarrollar
# ... hacer cambios en el código ...

# 4. Commits frecuentes
git add .
git commit -m "feat(sso): add WoWonder authentication middleware"

# ... más cambios ...
git commit -m "feat(sso): implement user sync from WoWonder"

# 5. Push de la feature
git push origin feature/wowonder-sso

# 6. Crear Pull Request en GitHub
# feature/wowonder-sso → main
# Título: "feat: WoWonder SSO integration"
# Descripción: Explicar qué hace y cómo probar

# 7. Después de merge y testing, actualizar main local
git checkout main
git pull origin main

# 8. Eliminar rama de feature
git branch -d feature/wowonder-sso
git push origin --delete feature/wowonder-sso
```

### Workflow 2: Fix Rápido

```bash
# 1. Desde main
git checkout main
git pull origin main

# 2. Crear rama de fix
git checkout -b fix/cart-price-calculation

# 3. Hacer el fix
# ... corregir el bug ...
git add .
git commit -m "fix(cart): correct tax calculation for MXN currency"

# 4. Push y PR
git push origin fix/cart-price-calculation
# Crear PR en GitHub: fix/cart-price-calculation → main

# 5. Después de merge
git checkout main
git pull origin main
git branch -d fix/cart-price-calculation
```

### Workflow 3: Recibir Updates de Bagisto

```bash
# 1. Fetch de upstream
git fetch upstream

# 2. Ver qué cambios hay
git log 2.3..upstream/2.3 --oneline

# Si hay cambios importantes:

# 3. Actualizar rama 2.3
git checkout 2.3
git merge upstream/2.3
git push origin 2.3

# 4. Opción A: Merge directo a main (si son fixes menores)
git checkout main
git merge 2.3 -m "chore: merge Bagisto 2.3 security updates"
# Resolver conflictos si hay
# Probar localmente
git push origin main

# 4. Opción B: Rama temporal para probar (si son cambios grandes)
git checkout main
git checkout -b update/bagisto-2.3.5
git merge 2.3
# Resolver conflictos
# Probar exhaustivamente
git push origin update/bagisto-2.3.5
# Crear PR: update/bagisto-2.3.5 → main
```

### Workflow 4: Deploy a Producción

```bash
# El deploy es automático cuando mergeas a main

# 1. Asegurarse que todo está probado localmente
git checkout main
docker compose restart
# ... testing exhaustivo ...

# 2. Merge del feature/fix via PR en GitHub

# 3. Pull en servidor de producción
ssh usuario@servidor
cd ~/apps/RamPlaza
git pull origin main

# 4. Ejecutar script de deploy
./devops/scripts/deploy.sh

# 5. Verificar
# - Check website
# - Check logs
# - Smoke testing
```

### Workflow 5: Release con Tag

```bash
# Cuando quieras marcar una versión importante

# 1. Asegurarte que main está estable
git checkout main
git pull origin main

# 2. Crear tag anotado
git tag -a v1.0.0 -m "Release v1.0.0

Features:
- WoWonder SSO integration
- Physical coupons with QR codes
- Spanish localization
- MXN currency support

Deployment:
- Production-ready
- Docker optimized
"

# 3. Push tag
git push origin v1.0.0

# 4. Crear Release en GitHub
# Releases → Create a new release
# Tag: v1.0.0
# Title: "RAM Plaza v1.0.0"
# Description: Copy del tag message
```

## 🛡️ Resolución de Conflictos

### Cuando hay conflictos entre upstream y customizaciones

```bash
# Durante merge de 2.3 a main
git checkout main
git merge 2.3

# Si hay conflictos:
# CONFLICT (content): Merge conflict in packages/Webkul/Shop/...

# 1. Ver archivos en conflicto
git status

# 2. Editar cada archivo manualmente
# Buscar marcadores: <<<<<<<, =======, >>>>>>>
# Decidir qué mantener:
#   - Cambios de Bagisto (upstream) = arriba de =======
#   - Cambios de RAM (tuyos) = abajo de =======
#   - O combinar ambos

# 3. Marcar como resuelto
git add path/to/conflicted/file

# 4. Continuar merge
git commit

# 5. Probar EXHAUSTIVAMENTE
docker compose restart
# Verificar toda la funcionalidad
```

### Estrategia de Resolución de Conflictos

**Prioridad al resolver:**

1. **Seguridad**: Si Bagisto tiene un fix de seguridad → tomar cambio de upstream
2. **Customizaciones de RAM**: Si es funcionalidad específica → mantener tu código
3. **Configuración**: Mantener config de RAM (español, MXN, etc.)
4. **Features nuevas de Bagisto**: Integrar con tus customizaciones (revisar cuidadosamente)

**Ejemplos:**

```bash
# Conflicto en config/app.php
<<<<<<< HEAD (tu código)
'locale' => 'es',
'currency' => 'MXN',
=======
'locale' => 'en',
'currency' => 'USD',
>>>>>>> 2.3 (Bagisto)

# Resolución: Mantener tu config
'locale' => 'es',
'currency' => 'MXN',
```

## 🎯 Convenciones de Commits

Usar [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>(<scope>): <description>

[optional body]
```

### Types

- `feat:` - Nueva funcionalidad
- `fix:` - Bug fix
- `chore:` - Mantenimiento (updates, configs)
- `docs:` - Documentación
- `style:` - Formato de código (sin cambios funcionales)
- `refactor:` - Refactorización (sin cambios funcionales)
- `test:` - Tests
- `perf:` - Mejoras de performance

### Scopes (opcional)

- `sso` - WoWonder SSO
- `coupons` - Sistema de cupones
- `cart` - Carrito de compras
- `checkout` - Proceso de checkout
- `admin` - Panel administrativo
- `devops` - Deployment y DevOps

### Ejemplos

```bash
# Features
git commit -m "feat(sso): implement WoWonder authentication middleware"
git commit -m "feat(coupons): add QR code generation for physical coupons"

# Fixes
git commit -m "fix(cart): correct tax calculation for MXN currency"
git commit -m "fix(checkout): resolve shipping address validation"

# Chores
git commit -m "chore: merge Bagisto 2.3.5 security updates"
git commit -m "chore(deps): update composer dependencies"

# Docs
git commit -m "docs: update deployment guide for production"
```

## 🔄 Sincronización Regular

### Frecuencia Recomendada

- **Check upstream**: Mensual o cuando haya release de Bagisto
- **Merge updates**: Cuando haya fixes de seguridad o features importantes
- **Deploy a producción**: Cuando completes features o fixes

### Script de Sincronización

```bash
#!/bin/bash
# devops/scripts/sync-upstream.sh

echo "🔄 Sincronizando con Bagisto upstream..."

# Fetch upstream
git fetch upstream

# Ver cambios
echo "📋 Cambios en upstream/2.3:"
git log 2.3..upstream/2.3 --oneline

# Contar commits
COMMITS=$(git log 2.3..upstream/2.3 --oneline | wc -l)

if [ $COMMITS -eq 0 ]; then
    echo "✅ Ya estás actualizado con upstream"
    exit 0
fi

echo ""
echo "Hay $COMMITS commits nuevos en Bagisto"
read -p "¿Ver detalles? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    git log 2.3..upstream/2.3 --stat
fi

echo ""
read -p "¿Merge estos cambios a rama 2.3? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    git checkout 2.3
    git merge upstream/2.3
    git push origin 2.3

    echo "✅ Rama 2.3 actualizada"
    echo ""
    echo "⚠️  Próximos pasos:"
    echo "1. Revisar los cambios"
    echo "2. Mergear a main: git checkout main && git merge 2.3"
    echo "3. O crear rama temporal: git checkout -b update/bagisto-new"
fi
```

## 📊 Visualización del Estado

### Comandos Útiles

```bash
# Ver todas las ramas
git branch -a

# Ver estado gráfico
git log --graph --oneline --all --decorate

# Ver commits en main que no están en 2.3
git log 2.3..main --oneline

# Ver commits en 2.3 que no están en main (updates de Bagisto)
git log main..2.3 --oneline

# Ver archivos modificados entre ramas
git diff 2.3 main --stat

# Ver quién modificó un archivo
git blame path/to/file
```

## 🎯 Checklist de Mantenimiento

### Mensual

- [ ] Verificar updates en upstream/2.3
- [ ] Merge updates de Bagisto si hay
- [ ] Actualizar dependencias: `composer update`, `npm update`
- [ ] Review issues de Bagisto en GitHub
- [ ] Backup de base de datos de producción

### Por Feature/Fix

- [ ] Crear rama desde main actualizado
- [ ] Desarrollo con commits frecuentes
- [ ] Testing local exhaustivo
- [ ] Push y crear Pull Request
- [ ] Review de código (si hay equipo)
- [ ] Merge a main
- [ ] Verificar en producción
- [ ] Eliminar rama temporal

### Por Release

- [ ] Testing exhaustivo
- [ ] Update de CHANGELOG
- [ ] Tag de versión (v1.x.x)
- [ ] Deploy a producción
- [ ] Verificación post-deploy
- [ ] Backup post-deploy
- [ ] Crear Release en GitHub

## 📚 Recursos

- [GitHub Flow](https://docs.github.com/en/get-started/quickstart/github-flow)
- [Bagisto GitHub](https://github.com/bagisto/bagisto)
- [Bagisto Docs](https://devdocs.bagisto.com/)
- [Conventional Commits](https://www.conventionalcommits.org/)

---

**Actualizado**: 2025-12-10
**Workflow**: GitHub Flow (simple y efectivo)
**Responsable**: Equipo RAM Plaza
