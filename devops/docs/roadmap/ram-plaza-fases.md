# RAM Plaza - Plan de Implementación por Fases

## Contexto

RAM Plaza es la sub-aplicación de comercio electrónico del ecosistema RAM (RedActivaMéxico). Funciona con un modelo híbrido:

- **Plaza General:** Todos los productos de todos los clientes en plaza.redactivamexico.net
- **Tiendas Premium:** Tiendas dedicadas con dominio propio para clientes premium

RAM crea y gestiona todo el contenido de los clientes. Los clientes no se auto-gestionan.

## Decisión Arquitectónica

**Multi-Channel de Bagisto** (nativo) en lugar de extensión Multi-Vendor porque:

- Control centralizado desde un solo panel admin
- Sin dependencias de extensiones de terceros
- Cada canal puede tener dominio y tema propio
- Actualizaciones de Bagisto más seguras

---

## FASE 1: Fundación (Identidad Visual)

**Objetivo:** Establecer la identidad visual RAM en la tienda.

**Estado:** PENDIENTE

### Tareas:

1. Crear child theme `ramplaza`
   - Ubicación: `resources/themes/ramplaza/`
   - Hereda del tema `default`
   - Solo sobrescribe header y footer

2. Aplicar estilos RAM
   - Header: Glassmorphism oscuro con transparencia
   - Footer: Mismo estilo glassmorphism
   - Colores: Pink #ff3e9a (acento), #1E252B (fondo oscuro)
   - CSS estático en `public/ram-assets/shop-theme.css`

3. Configurar tema
   - Actualizar `config/themes.php` con tema ramplaza
   - Actualizar canal en base de datos para usar ramplaza
   - Limpiar caches

4. Verificar
   - Probar en navegador
   - Verificar que productos cargan
   - Verificar responsive

### Archivos a crear/modificar:

```
resources/themes/ramplaza/
├── views/
│   └── components/
│       └── layouts/
│           ├── header/
│           │   └── index.blade.php
│           └── footer/
│               └── index.blade.php

public/ram-assets/
└── shop-theme.css

config/themes.php (modificar)
```

### Criterios de éxito:

- Header y footer con estilo glassmorphism visible
- Resto de la tienda funciona normalmente (hereda del default)
- Sin errores en consola del navegador

---

## FASE 2: Contenido Base

**Objetivo:** Tener contenido real para demostrar la tienda.

**Estado:** PENDIENTE

### Tareas:

1. Definir categorías iniciales
   - Productos RAM (merch corporativo)
   - Equipo para Creadores (micrófonos, cámaras)
   - Productos Digitales (suscripciones, plantillas)

2. Crear productos de ejemplo
   - Mínimo 5-10 productos con imágenes reales
   - Precios en MXN
   - Descripciones completas

3. Configurar métodos de pago
   - Stripe o PayPal para México
   - Transferencia bancaria (opcional)

4. Configurar envíos
   - Zonas de envío en México
   - Tarifas por peso/zona

### Criterios de éxito:

- Carrito funcional
- Checkout completo (hasta pago)
- Emails de confirmación funcionando

---

## FASE 3: Multi-Channel (Tiendas Premium)

**Objetivo:** Permitir tiendas dedicadas para clientes premium.

**Estado:** PENDIENTE

### Tareas:

1. Configurar wildcard subdomain
   - *.ramplaza.mx apuntando al servidor
   - Nginx configurado para subdominios dinámicos

2. Documentar proceso de nuevo canal
   - Crear categoría raíz para el cliente
   - Crear canal con hostname específico
   - Asignar tema (base o personalizado)

3. Crear primer canal de prueba
   - Cliente ficticio de ejemplo
   - Verificar aislamiento de productos
   - Verificar dominio funciona

4. Crear sistema de temas por cliente (opcional)
   - Variaciones del tema ramplaza
   - Colores personalizables por canal

### Criterios de éxito:

- Subdominio cliente.ramplaza.mx funciona
- Muestra solo productos del cliente
- Comparte base de datos con plaza general

---

## FASE 4: Integración con Ecosistema RAM

**Objetivo:** Conectar RAM Plaza con las otras apps RAM.

**Estado:** FUTURO

### Tareas:

1. API endpoints para otras apps
   - GET productos de un cliente
   - GET categorías
   - Webhooks de nuevos productos

2. Single Sign-On con RAM Social
   - Login unificado con RAM Core
   - Perfil de usuario compartido

3. Promoción cruzada
   - Widget de productos en RAM Social
   - Links desde RAM Música/IPTV
   - Productos en fichas de RAM Mapas

### Dependencias:

- RAM Core (Wowwonder) - Feed Comercial listo
- APIs definidas entre apps
- Autenticación unificada

---

## Estado General

| Fase | Estado | Progreso |
|------|--------|----------|
| Fase 1: Fundación | PENDIENTE | 0% |
| Fase 2: Contenido | PENDIENTE | 0% |
| Fase 3: Multi-Channel | PENDIENTE | 0% |
| Fase 4: Integración | FUTURO | 0% |

## Infraestructura (Completada)

- [x] Docker configurado y funcionando
- [x] Nginx con SSL
- [x] Base de datos MySQL
- [x] Redis para cache
- [x] Elasticsearch para búsqueda
- [x] Queue worker para jobs
- [x] Scheduler para tareas programadas
- [x] Fix de nginx para imágenes storage/webp

---

*Última actualización: Diciembre 2025*
