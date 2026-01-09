# Integración RAM - RAMPlaza

## Visión General

RAMPlaza opera como **Service Provider (SP)** dentro del ecosistema RAM, delegando toda la gestión de identidad al **Identity Provider (IdP)** central: RAM.

Este modelo replica la arquitectura de Google con sus sub-aplicaciones (YouTube, Drive, Gmail), donde existe una única fuente de verdad para la identidad del usuario.

```
┌─────────────────────────────────────────────────────────────┐
│                    Ecosistema RAM                           │
│                                                             │
│   ┌─────────┐     ┌─────────────┐     ┌─────────────┐      │
│   │   RAM   │────▶│  RAMPlaza   │     │  Muro Loco  │      │
│   │  (IdP)  │◀────│ (E-commerce)│     │   (Feed)    │      │
│   └─────────┘     └─────────────┘     └─────────────┘      │
│        │                                    │               │
│        └────────────────────────────────────┘               │
│              Single Sign-On + Identidad Compartida          │
└─────────────────────────────────────────────────────────────┘
```

## Bounded Contexts (DDD)

### Identity Context (RAM)
- **Aggregate Root:** User
- **Responsabilidades:** Autenticación, credenciales, perfil, sesiones
- **Ownership:** RAM es la única fuente de verdad

### Commerce Context (RAMPlaza)
- **Aggregate Root:** Customer
- **Responsabilidades:** Órdenes, carrito, direcciones, historial de compras
- **Relación:** Customer referencia a User vía `provider_id`

## Patrones de Integración

### 1. OAuth 2.0 (Autenticación Principal)

RAMPlaza no tiene formularios nativos de login/registro. Toda autenticación fluye a través de RAM usando OAuth 2.0 Authorization Code flow.

**Comportamiento clave:**
- RAMPlaza es una "trusted app" (primera parte)
- El usuario nunca ve pantalla de autorización
- Auto-provisioning: se crea Customer automáticamente en primer login

### 2. Auto-Login (Navegación Seamless)

Cuando un usuario navega desde RAM hacia RAMPlaza (ej: click en producto desde Muro Loco), se autentica automáticamente sin intervención.

**Mecanismo:** URLs firmadas con HMAC-SHA256
- Validez temporal limitada
- Vincula identidad + timestamp + secreto compartido

### 3. Logout Sincronizado

Logout desde cualquier sistema termina sesiones en ambos mediante cadena de redirects.

```
Plaza logout → RAM logout → Plaza home (logged out)
RAM logout → Plaza logout → RAM home (logged out)
```

### 4. Gestión de Perfil

- El perfil del usuario se gestiona exclusivamente en RAM
- RAMPlaza muestra datos read-only con link a RAM
- Cambio de contraseña solo disponible en RAM

## Anti-Corruption Layer

RAMPlaza traduce conceptos de RAM a su propio modelo:

| RAM | RAMPlaza |
|-----|----------|
| `user_id` | `provider_id` en CustomerSocialAccount |
| `first_name`, `last_name` | Copiados a Customer en provisioning |
| `email` | Identificador único del Customer |
| `password` | No almacenado (gestionado por RAM) |

## Manejo de Errores

Errores de OAuth se mapean a mensajes user-friendly en español. El usuario puede reintentar sin ver detalles técnicos.

Categorías:
- **Configuración:** Requiere intervención de administrador
- **Sesión expirada:** Reintentar login
- **Conexión:** Esperar y reintentar

## Documentación Oficial

La documentación técnica completa de la integración se encuentra en el repositorio RAM:

```
RAM/ram/docs/RAMPlaza/
├── README.md          # Overview y configuración
├── domain-model.md    # Modelo de dominio DDD completo
├── oauth-flow.md      # Flujo OAuth detallado
├── auto-login.md      # Especificación de URLs firmadas
├── logout-sync.md     # Sincronización de logout
├── error-handling.md  # Códigos de error y mensajes
└── configuration.md   # Guía de configuración
```

## Archivos Clave en RAMPlaza

| Archivo | Propósito |
|---------|-----------|
| `packages/Webkul/SocialLogin/src/Providers/RamProvider.php` | Provider OAuth 2.0 |
| `packages/Webkul/SocialLogin/src/Http/Controllers/LoginController.php` | Callbacks OAuth |
| `packages/Webkul/Shop/src/Http/Middleware/RamAutoLogin.php` | Validación de URLs firmadas |
| `packages/Webkul/Shop/src/Http/Controllers/Customer/SessionController.php` | Logout sync |
| `packages/Webkul/Admin/src/Config/system.php` | Campos de configuración admin |

## Configuración

La configuración se realiza en el Admin Panel:
**Configuration > Customer > Settings > Social Login**

Los valores específicos (URLs, tokens, secrets) se documentan internamente y no deben commitearse al repositorio.

## Work Items Relacionados

- **#191:** Integración OAuth RAM ↔ RAMPlaza