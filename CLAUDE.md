# RAM Plaza - Reglas de Desarrollo

## Proyecto
- **Nombre:** RAM Plaza
- **Base:** Bagisto 2.x (Laravel e-commerce)
- **Propósito:** E-commerce de RedActivaMéxico (NO es marketplace)
- **URL Producción:** https://plaza.redactivamexico.net

---

## Arquitectura de Bagisto

### Sistema de Temas
Bagisto usa **Vite** para compilar assets. La configuración está en `config/themes.php`.

```
Estructura de un tema:
├── packages/Webkul/Shop/src/Resources/
│   ├── assets/          ← Fuentes (CSS, JS, imágenes)
│   │   ├── css/app.css
│   │   ├── js/app.js
│   │   └── images/
│   └── views/           ← Vistas Blade
│
└── public/themes/shop/default/build/  ← Assets COMPILADOS
    ├── manifest.json
    └── assets/
```

### Tipos de Archivos

| Tipo | Ubicación | ¿Editar? | ¿Commitear? |
|------|-----------|----------|-------------|
| Fuentes CSS/JS | `packages/*/src/Resources/assets/` | Sí | Sí |
| Assets Compilados | `public/themes/*/build/` | NO | **SÍ** |
| Vistas Blade | `packages/*/src/Resources/views/` | Sí | Sí |
| Configuración | `config/` | Sí | Sí |

---

## Reglas de Edición de Código

### NUNCA hacer:
1. **NO editar archivos compilados** en `public/themes/*/build/` directamente
2. **NO ejecutar `npm run build` sin commitear después** - genera archivos con hash diferentes
3. **NO revertir parcialmente** - si reviertes fuentes, revierte también compilados
4. **NO eliminar archivos en `public/themes/*/build/`** sin recompilar

### SIEMPRE hacer:
1. **Editar fuentes** → `npm run build` → **commitear TODO junto** (fuentes + compilados)
2. **Limpiar cache después de cambios en vistas Blade:**
   ```bash
   docker exec ramplaza-app php artisan view:clear
   docker exec ramplaza-app php artisan cache:clear
   ```
3. **Verificar estado de git** antes y después de cambios:
   ```bash
   git status
   git diff --name-only
   ```

---

## Flujo de Trabajo para Cambios

### Cambios en Vistas Blade (sin CSS/JS)
```bash
# 1. Editar archivo .blade.php
# 2. Limpiar cache
docker exec ramplaza-app php artisan view:clear

# 3. Probar cambios
# 4. Commitear
git add <archivo.blade.php>
git commit -m "feat(shop): descripción del cambio"
```

### Cambios en CSS/JS (requiere compilación)
```bash
# 1. Editar fuentes en packages/*/src/Resources/assets/

# 2. Instalar dependencias (si no existen)
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm install"

# 3. Compilar assets
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Shop && npm run build"

# 4. Limpiar cache
docker exec ramplaza-app php artisan cache:clear

# 5. Commitear TODO junto (fuentes + compilados)
git add packages/Webkul/Shop/src/Resources/assets/
git add public/themes/shop/default/build/
git commit -m "feat(shop): descripción del cambio"
```

### Para Admin (similar pero diferente paquete)
```bash
# Compilar Admin assets
docker exec ramplaza-app bash -c "cd /var/www/html/packages/Webkul/Admin && npm install && npm run build"
git add packages/Webkul/Admin/src/Resources/assets/
git add public/themes/admin/default/build/
```

---

## Método Recomendado: Theme Package Propio

Bagisto recomienda NO editar el core. Crear un paquete propio:

```
packages/
└── RAMPlaza/
    └── Theme/
        ├── composer.json
        └── src/
            ├── Providers/
            │   └── ThemeServiceProvider.php
            └── Resources/
                ├── assets/
                │   ├── css/
                │   └── js/
                └── views/
```

**Ventajas:**
- Cambios sobreviven actualizaciones de Bagisto
- Separación clara core vs customizaciones
- Fácil de versionar y distribuir

**Proceso para crear:**
1. Crear estructura de directorios
2. Crear ServiceProvider con método `boot()` para publicar vistas
3. Registrar en `bootstrap/providers.php`
4. Configurar en `config/themes.php`
5. Ejecutar `composer dump-autoload`

---

## Customización sin Compilación

### Inyectar CSS personalizado (método seguro)
Crear archivo CSS estático y cargarlo en el layout:

```php
// En packages/Webkul/Shop/src/Resources/views/components/layouts/index.blade.php
@push('styles')
    <link rel="stylesheet" href="{{ asset('ram-assets/custom.css') }}">
@endpush
```

Los archivos en `public/ram-assets/` NO requieren compilación.

### CSS desde Admin Panel
Configuración > Content > Custom Scripts > Custom CSS
- No requiere código
- Se inyecta globalmente
- Útil para cambios menores

---

## Entorno Docker

### Contenedores
```
ramplaza-app          ← PHP/Laravel (aquí se ejecutan comandos artisan y npm)
ramplaza-webserver    ← Nginx
ramplaza-mysql        ← Base de datos
ramplaza-redis        ← Cache
ramplaza-elasticsearch ← Búsqueda
ramplaza-queue        ← Jobs en background
ramplaza-scheduler    ← Cron jobs
```

### Comandos Frecuentes
```bash
# Cache
docker exec ramplaza-app php artisan cache:clear
docker exec ramplaza-app php artisan view:clear
docker exec ramplaza-app php artisan config:clear
docker exec ramplaza-app php artisan route:clear

# Optimizar (producción)
docker exec ramplaza-app php artisan optimize

# Limpiar todo
docker exec ramplaza-app php artisan optimize:clear

# Logs
docker logs ramplaza-app --tail 100
docker logs ramplaza-webserver --tail 100

# Shell interactivo
docker exec -it ramplaza-app bash
```

---

## Identidad Visual RAM

### Colores
```css
--ram-primary: #ff3e9a;      /* Neon Pink */
--ram-secondary: #ff66b6;    /* Pink claro */
--ram-bg: #1E252B;           /* Fondo oscuro */
--ram-form-bg: #262D34;      /* Fondo formularios */
```

### Glassmorphism RAM
```css
background: rgba(255, 255, 255, 0.08);
backdrop-filter: blur(16px);
-webkit-backdrop-filter: blur(16px);
border: 1px solid rgba(255, 255, 255, 0.15);
border-radius: 20px;
```

### Assets RAM existentes
```
public/ram-assets/
├── ram-identity.css         ← Estilos del admin login
└── login-bg-optimized.jpg   ← Fondo optimizado (275KB)
```

---

## Problemas Conocidos

### Lazy Loading de Imágenes
El tema default de Bagisto tiene un bug donde las imágenes con lazy loading no cargan hasta redimensionar la ventana. El `IntersectionObserver` no detecta elementos ya visibles al mount.

**Solución pendiente:** Crear theme package propio con el fix, o inyectar JS correctivo.

### Storage/Uploads
Las imágenes subidas requieren que nginx tenga acceso al volumen de storage:
```yaml
# docker-compose.prod.yml
nginx:
  volumes:
    - ./storage/app/public:/var/www/html/public/storage
```

---

## Commits

Usar Conventional Commits:
```
feat(shop): add RAM branding to storefront
fix(docker): add storage volume mount for nginx
chore(config): update theme settings
style(admin): apply glassmorphism to login
```

---

## Referencias

- [Bagisto Docs - Store Theme](https://devdocs.bagisto.com/theme-development/creating-store-theme)
- [Bagisto Docs - Vite Assets](https://devdocs.bagisto.com/theme-development/vite-powered-theme-assets.html)
- [Bagisto Docs - Custom Theme Package](https://devdocs.bagisto.com/theme-development/creating-custom-theme-package.html)
- [RAM Visual Identity](~/apps/RAM/ram/docs/visual-identity.md)
