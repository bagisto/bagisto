# Plan de Social Commerce - RAM Comercios

**Objetivo:** Integrar feed social de productos y promociones tipo TikTok/Instagram Shopping con sistema robusto de cupones.

**Fecha:** 2025-12-10
**Estado:** Planificación

---

## 🎯 Requisitos del Usuario

### Funcionalidades Requeridas

1. **Feed Social de Productos**
   - Feed estilo TikTok/Instagram con productos
   - Posts de promociones y descuentos
   - Interacción social (likes, comments, shares)
   - Scroll vertical infinito
   - Destacar ofertas y cupones

2. **Sistema de Cupones Robusto**
   - Cupones digitales con códigos
   - Cupones físicos con QR codes
   - Descuentos por porcentaje y monto fijo
   - Límites por usuario y fechas de expiración
   - Validación en tienda física (QR scan)

3. **Integración con Ecosystem Existente**
   - SSO con WoWonder (usuarios de RAM)
   - Aprovechar base de usuarios existente
   - Experiencia unificada en redactivamexico.net

---

## 📊 Análisis del Mercado

### Realidad de Plataformas Open Source

**Conclusión:** NO existe plataforma open source que combine nativamente:
- ✅ E-commerce multi-vendor
- ✅ Feed social de productos
- ✅ Modificar código
- ✅ Recibir updates

**Opciones en el mercado:**

| Tipo | Ejemplos | Feed Social | Modificable | Updates |
|------|----------|-------------|-------------|---------|
| **Social Commerce** | TikTok Shop, Instagram Shopping | ✅ | ❌ | ❌ |
| **E-commerce Open Source** | Bagisto, Magento, Aimeos | ❌ | ✅ | ✅ |
| **Híbridos Open Source** | No existen | ❌ | - | - |

### Mejor Estrategia

**Arquitectura híbrida:** Separar responsabilidades entre plataformas especializadas.

---

## 🏗️ Opciones de Arquitectura

### Opción 1A: WoWonder Feed + Bagisto (Original)

**Concepto:** Posts de productos en el feed principal de WoWonder.

```
┌─────────────────────────────────────────┐
│  Feed Principal WoWonder                │
├─────────────────────────────────────────┤
│ 👤 Juan publicó una foto                │
│    [imagen social]                      │
├─────────────────────────────────────────┤
│ 🛍️  PRODUCTO: iPhone 15 Pro -30%       │
│    💰 Cupón: RAM30                      │
│    🔗 Comprar ahora                     │
├─────────────────────────────────────────┤
│ 👤 María compartió un video             │
│    [video social]                       │
├─────────────────────────────────────────┤
│ 🛍️  PROMO: Nike Air Max Flash Sale     │
│    💰 Solo hoy 50% OFF                  │
│    🔗 Ver más                           │
└─────────────────────────────────────────┘
```

**Ventajas:**
- ✅ Mayor visibilidad (todos los usuarios ven productos)
- ✅ Contenido mixto (social + comercial)
- ✅ Aprovecha algoritmo de feed existente

**Desventajas:**
- ⚠️ Puede "contaminar" feed social
- ⚠️ Usuarios pueden cansarse de posts comerciales
- ⚠️ Difícil filtrar solo productos

---

### 🌟 Opción 1B: "MURO LOCO" - Feed Dedicado (RECOMENDADA)

**Concepto:** Feed separado dentro de WoWonder, exclusivo para productos, estilo TikTok.

```
WoWonder Navigation:
┌─────────────────────────────────────────┐
│ 🏠 Inicio  |  🛍️ MURO LOCO  |  👤 Perfil│
└─────────────────────────────────────────┘

Cuando usuario entra a "MURO LOCO":
┌─────────────────────────────────────────┐
│          🛍️  MURO LOCO                  │
│      Descubre ofertas increíbles        │
├─────────────────────────────────────────┤
│                                         │
│  📱 [Imagen grande del producto]        │
│     iPhone 15 Pro Max                   │
│     $24,999 MXN  $17,499 MXN           │
│     💥 30% OFF - Solo hoy              │
│                                         │
│     💰 Cupón: RAM30                     │
│     [Copiar código] [Comprar ahora]     │
│                                         │
│     ❤️ 234   💬 45   🔗 Compartir      │
│                                         │
│  ↓ Scroll para siguiente producto      │
├─────────────────────────────────────────┤
│  🏷️  Filtros: 📱 Tech | 👕 Ropa        │
└─────────────────────────────────────────┘
```

**Características del Muro Loco:**

1. **Interfaz Estilo TikTok**
   - Scroll vertical infinito
   - Un producto por pantalla (fullscreen)
   - Swipe up para siguiente producto
   - Video/imagen grande

2. **Interacción Social**
   - Like al producto
   - Comentarios sobre ofertas
   - Compartir en feed principal de WoWonder
   - Guardar para después

3. **Información de Producto**
   - Precio original y con descuento
   - Código de cupón destacado
   - Botón directo "Comprar ahora"
   - Stock disponible
   - Tiempo restante de oferta (countdown)

4. **Algoritmo de Feed**
   - Productos personalizados por intereses
   - Ofertas más populares primero
   - Prioridad a productos con descuento activo
   - Rotación de promociones

**Arquitectura Técnica:**

```
┌──────────────────────────────────────────────────────┐
│                    USUARIO                           │
└────────────┬─────────────────────────────────────────┘
             │
    ┌────────▼────────┐
    │   WoWonder      │
    │  (Frontend)     │
    ├─────────────────┤
    │ • Feed Normal   │
    │ • MURO LOCO ⭐  │ ← Nueva sección
    │ • Perfil        │
    └────────┬────────┘
             │
    ┌────────▼──────────────────────────────┐
    │  Nueva Tabla: muro_loco_posts         │
    ├───────────────────────────────────────┤
    │ • post_id                             │
    │ • product_id (Bagisto)                │
    │ • user_id                             │
    │ • title                               │
    │ • description                         │
    │ • image/video                         │
    │ • price_original                      │
    │ • price_discount                      │
    │ • coupon_code                         │
    │ • bagisto_url                         │
    │ • likes_count                         │
    │ • views_count                         │
    │ • created_at                          │
    └────────┬──────────────────────────────┘
             │
    ┌────────▼────────┐
    │     Bagisto     │
    │  (E-commerce)   │
    ├─────────────────┤
    │ • Products      │
    │ • Coupons       │
    │ • Orders        │
    │ • Checkout      │
    └─────────────────┘
```

**Ventajas de "Muro Loco":**
- ✅ **Experiencia dedicada** a shopping
- ✅ **No contamina** el feed social principal
- ✅ **Usuarios van intencionalmente** a ver ofertas
- ✅ **Algoritmo específico** para productos
- ✅ **UX optimizada** para compras (no social)
- ✅ **Métricas separadas** (engagement de productos)
- ✅ **Nombre catchy** y memorable

**Desventajas:**
- ⚠️ Usuarios necesitan hacer click extra (ir a Muro Loco)
- ⚠️ Menos visibilidad que feed principal
- ⚠️ Necesita promoción para que usuarios lo conozcan

---

### Opción 2: Feed Custom Solo en Bagisto

**Concepto:** Desarrollar módulo de feed social dentro de Bagisto únicamente.

```
comercios.redactivamexico.net
├── /feed (nuevo feed de productos)
├── /products (catálogo tradicional)
└── /checkout
```

**Ventajas:**
- ✅ Todo en una plataforma
- ✅ Código unificado

**Desventajas:**
- ❌ Usuarios necesitan cuenta separada
- ❌ No aprovecha base de usuarios de WoWonder
- ❌ Desarrollar feed desde cero (4-5 semanas)
- ❌ Re-inventar características sociales

**Veredicto:** No recomendado.

---

## 🎯 Recomendación Final: MURO LOCO

### Arquitectura Elegida

```
redactivamexico.net (WoWonder)
├── /feed (posts sociales normales)
│
├── /muro-loco ⭐ (NUEVO - feed de productos)
│   ├── Scroll vertical infinito
│   ├── Posts de productos con cupones
│   ├── Integración con Bagisto
│   └── Interacción social (like, comment, share)
│
├── /store → Redirect a comercios.redactivamexico.net
│
└── Usuario logueado globalmente (SSO)

comercios.redactivamexico.net (Bagisto)
├── /products (catálogo completo)
├── /cart
├── /checkout
└── /admin
    └── Crear promoción → Auto-post en Muro Loco
```

---

## 🛠️ Implementación Técnica

### Fase 1: Base de Datos (WoWonder)

```sql
-- Nueva tabla para posts de Muro Loco
CREATE TABLE muro_loco_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id VARCHAR(100) NOT NULL,  -- ID from Bagisto
    user_id INT NOT NULL,  -- Usuario que publicó (admin/vendor)

    -- Contenido
    title VARCHAR(255) NOT NULL,
    description TEXT,
    media_type ENUM('image', 'video') DEFAULT 'image',
    media_url VARCHAR(500) NOT NULL,

    -- Pricing
    price_original DECIMAL(10,2),
    price_discount DECIMAL(10,2),
    discount_percentage INT,

    -- Cupón
    coupon_code VARCHAR(50),
    coupon_expires_at DATETIME,

    -- Enlaces
    bagisto_product_url VARCHAR(500) NOT NULL,

    -- Métricas
    views_count INT DEFAULT 0,
    likes_count INT DEFAULT 0,
    comments_count INT DEFAULT 0,
    clicks_count INT DEFAULT 0,

    -- Estado
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,

    -- Categorización
    category_id INT,
    tags JSON,

    -- Timestamps
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_user (user_id),
    INDEX idx_product (product_id),
    INDEX idx_active (is_active, published_at),
    INDEX idx_featured (is_featured),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla para likes en Muro Loco
CREATE TABLE muro_loco_likes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_like (post_id, user_id),
    FOREIGN KEY (post_id) REFERENCES muro_loco_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla para comentarios en Muro Loco
CREATE TABLE muro_loco_comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_post (post_id),
    FOREIGN KEY (post_id) REFERENCES muro_loco_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla para tracking de clicks
CREATE TABLE muro_loco_clicks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    clicked_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_post (post_id),
    FOREIGN KEY (post_id) REFERENCES muro_loco_posts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Fase 2: Backend API (WoWonder/PHP)

```php
// ajax/muro-loco/get-feed.php
<?php
header('Content-Type: application/json');
require_once('../../config.php');

$user_id = $_SESSION['user_id'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Algoritmo de feed personalizado
$posts = $db->query("
    SELECT
        ml.*,
        u.username, u.avatar, u.verified,
        (SELECT COUNT(*) FROM muro_loco_likes WHERE post_id = ml.id) as likes_count,
        (SELECT COUNT(*) FROM muro_loco_comments WHERE post_id = ml.id) as comments_count,
        EXISTS(SELECT 1 FROM muro_loco_likes WHERE post_id = ml.id AND user_id = ?) as user_liked
    FROM muro_loco_posts ml
    JOIN users u ON ml.user_id = u.id
    WHERE ml.is_active = 1
      AND ml.published_at <= NOW()
    ORDER BY
        ml.is_featured DESC,
        ml.published_at DESC
    LIMIT ? OFFSET ?
", [$user_id, $limit, $offset]);

echo json_encode([
    'success' => true,
    'posts' => $posts,
    'has_more' => count($posts) === $limit
]);
```

```php
// ajax/muro-loco/like-post.php
<?php
require_once('../../config.php');

$post_id = intval($_POST['post_id']);
$user_id = $_SESSION['user_id'];

// Toggle like
$exists = $db->query("
    SELECT id FROM muro_loco_likes
    WHERE post_id = ? AND user_id = ?
", [$post_id, $user_id]);

if ($exists) {
    // Unlike
    $db->query("DELETE FROM muro_loco_likes WHERE post_id = ? AND user_id = ?",
        [$post_id, $user_id]);
    $liked = false;
} else {
    // Like
    $db->query("INSERT INTO muro_loco_likes (post_id, user_id) VALUES (?, ?)",
        [$post_id, $user_id]);
    $liked = true;
}

// Get new count
$likes_count = $db->getValue("
    SELECT COUNT(*) FROM muro_loco_likes WHERE post_id = ?
", [$post_id]);

echo json_encode([
    'success' => true,
    'liked' => $liked,
    'likes_count' => $likes_count
]);
```

```php
// ajax/muro-loco/track-click.php
<?php
require_once('../../config.php');

$post_id = intval($_POST['post_id']);
$user_id = $_SESSION['user_id'] ?? null;

// Track click
$db->query("
    INSERT INTO muro_loco_clicks (post_id, user_id, ip_address, user_agent)
    VALUES (?, ?, ?, ?)
", [$post_id, $user_id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);

// Increment counter
$db->query("UPDATE muro_loco_posts SET clicks_count = clicks_count + 1 WHERE id = ?",
    [$post_id]);

echo json_encode(['success' => true]);
```

### Fase 3: Frontend (WoWonder/Vue.js o JavaScript)

```vue
<!-- themes/default/statics/muro-loco.html -->
<template>
  <div class="muro-loco-container">
    <!-- Header -->
    <div class="muro-loco-header">
      <h1>🛍️ MURO LOCO</h1>
      <p>Descubre ofertas increíbles</p>

      <!-- Filtros -->
      <div class="filters">
        <button @click="filterCategory('all')" :class="{active: category === 'all'}">
          Todos
        </button>
        <button @click="filterCategory('tech')" :class="{active: category === 'tech'}">
          📱 Tech
        </button>
        <button @click="filterCategory('fashion')" :class="{active: category === 'fashion'}">
          👕 Moda
        </button>
        <button @click="filterCategory('home')" :class="{active: category === 'home'}">
          🏠 Hogar
        </button>
      </div>
    </div>

    <!-- Feed de productos (Scroll vertical) -->
    <div class="muro-loco-feed" @scroll="handleScroll">
      <div
        v-for="post in posts"
        :key="post.id"
        class="product-card"
      >
        <!-- Media (imagen o video) -->
        <div class="media-container">
          <img
            v-if="post.media_type === 'image'"
            :src="post.media_url"
            :alt="post.title"
          />
          <video
            v-else
            :src="post.media_url"
            autoplay
            loop
            muted
            playsinline
          ></video>
        </div>

        <!-- Info overlay -->
        <div class="product-info">
          <!-- Usuario que publicó -->
          <div class="publisher">
            <img :src="post.avatar" class="avatar">
            <span class="username">{{ post.username }}</span>
            <span v-if="post.verified" class="verified">✓</span>
          </div>

          <!-- Título y descripción -->
          <h2 class="product-title">{{ post.title }}</h2>
          <p class="product-description">{{ post.description }}</p>

          <!-- Pricing -->
          <div class="pricing">
            <span class="original-price">${{ post.price_original }}</span>
            <span class="discount-price">${{ post.price_discount }}</span>
            <span class="discount-badge">{{ post.discount_percentage }}% OFF</span>
          </div>

          <!-- Cupón -->
          <div v-if="post.coupon_code" class="coupon-box">
            <span class="coupon-label">💰 Cupón:</span>
            <span class="coupon-code">{{ post.coupon_code }}</span>
            <button @click="copyCoupon(post.coupon_code)" class="copy-btn">
              Copiar
            </button>
          </div>

          <!-- Countdown si hay expiración -->
          <div v-if="post.coupon_expires_at" class="countdown">
            ⏰ Termina en {{ getCountdown(post.coupon_expires_at) }}
          </div>

          <!-- CTA Button -->
          <button
            @click="goToProduct(post)"
            class="buy-btn"
          >
            🛒 Comprar Ahora
          </button>

          <!-- Interacción social -->
          <div class="interactions">
            <button
              @click="toggleLike(post)"
              :class="['like-btn', {liked: post.user_liked}]"
            >
              ❤️ {{ post.likes_count }}
            </button>

            <button @click="showComments(post)" class="comment-btn">
              💬 {{ post.comments_count }}
            </button>

            <button @click="sharePost(post)" class="share-btn">
              🔗 Compartir
            </button>
          </div>
        </div>
      </div>

      <!-- Loading indicator -->
      <div v-if="loading" class="loading">
        <div class="spinner"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MuroLoco',

  data() {
    return {
      posts: [],
      page: 1,
      loading: false,
      hasMore: true,
      category: 'all'
    }
  },

  mounted() {
    this.loadFeed();
  },

  methods: {
    async loadFeed() {
      if (this.loading || !this.hasMore) return;

      this.loading = true;

      try {
        const response = await fetch(`/ajax/muro-loco/get-feed.php?page=${this.page}&category=${this.category}`);
        const data = await response.json();

        this.posts.push(...data.posts);
        this.hasMore = data.has_more;
        this.page++;
      } catch (error) {
        console.error('Error loading feed:', error);
      } finally {
        this.loading = false;
      }
    },

    handleScroll(e) {
      const { scrollTop, scrollHeight, clientHeight } = e.target;

      // Load more when 80% scrolled
      if (scrollTop + clientHeight >= scrollHeight * 0.8) {
        this.loadFeed();
      }
    },

    async toggleLike(post) {
      try {
        const formData = new FormData();
        formData.append('post_id', post.id);

        const response = await fetch('/ajax/muro-loco/like-post.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          post.user_liked = data.liked;
          post.likes_count = data.likes_count;
        }
      } catch (error) {
        console.error('Error toggling like:', error);
      }
    },

    async goToProduct(post) {
      // Track click
      try {
        const formData = new FormData();
        formData.append('post_id', post.id);

        await fetch('/ajax/muro-loco/track-click.php', {
          method: 'POST',
          body: formData
        });
      } catch (error) {
        console.error('Error tracking click:', error);
      }

      // Redirect to Bagisto with coupon pre-applied
      let url = post.bagisto_product_url;
      if (post.coupon_code) {
        url += `?coupon=${post.coupon_code}`;
      }

      window.location.href = url;
    },

    copyCoupon(code) {
      navigator.clipboard.writeText(code);
      // Show toast notification
      this.$toast.success('Cupón copiado!');
    },

    getCountdown(expiresAt) {
      const now = new Date();
      const expires = new Date(expiresAt);
      const diff = expires - now;

      const hours = Math.floor(diff / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

      return `${hours}h ${minutes}m`;
    },

    filterCategory(category) {
      this.category = category;
      this.posts = [];
      this.page = 1;
      this.hasMore = true;
      this.loadFeed();
    },

    sharePost(post) {
      // Share via WoWonder native sharing
      if (navigator.share) {
        navigator.share({
          title: post.title,
          text: `${post.title} - ${post.discount_percentage}% OFF!`,
          url: window.location.href
        });
      }
    },

    showComments(post) {
      // Open comments modal (implementation depends on WoWonder)
      window.showCommentsModal(post.id);
    }
  }
}
</script>

<style scoped>
.muro-loco-container {
  max-width: 100%;
  height: 100vh;
  overflow: hidden;
}

.muro-loco-header {
  padding: 20px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  text-align: center;
}

.filters {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-top: 15px;
}

.filters button {
  padding: 8px 16px;
  border: 2px solid white;
  background: transparent;
  color: white;
  border-radius: 20px;
  cursor: pointer;
  transition: all 0.3s;
}

.filters button.active {
  background: white;
  color: #667eea;
}

.muro-loco-feed {
  height: calc(100vh - 150px);
  overflow-y: auto;
  scroll-snap-type: y mandatory;
  -webkit-overflow-scrolling: touch;
}

.product-card {
  position: relative;
  width: 100%;
  height: calc(100vh - 150px);
  scroll-snap-align: start;
  display: flex;
  align-items: center;
  justify-content: center;
}

.media-container {
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.media-container img,
.media-container video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-info {
  position: relative;
  z-index: 2;
  padding: 20px;
  background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);
  width: 100%;
  color: white;
}

.publisher {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

.product-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 10px;
}

.pricing {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 15px 0;
}

.original-price {
  text-decoration: line-through;
  opacity: 0.7;
  font-size: 18px;
}

.discount-price {
  font-size: 28px;
  font-weight: bold;
  color: #4ade80;
}

.discount-badge {
  background: #ef4444;
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 14px;
  font-weight: bold;
}

.coupon-box {
  background: rgba(255,255,255,0.1);
  border: 2px dashed white;
  padding: 15px;
  border-radius: 10px;
  margin: 15px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.coupon-code {
  font-family: monospace;
  font-size: 20px;
  font-weight: bold;
  letter-spacing: 2px;
}

.buy-btn {
  width: 100%;
  padding: 15px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 10px;
  color: white;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  margin: 20px 0;
  transition: transform 0.2s;
}

.buy-btn:hover {
  transform: scale(1.05);
}

.interactions {
  display: flex;
  gap: 20px;
  margin-top: 20px;
}

.interactions button {
  background: rgba(255,255,255,0.2);
  border: none;
  padding: 10px 20px;
  border-radius: 20px;
  color: white;
  cursor: pointer;
}

.like-btn.liked {
  background: #ef4444;
}
</style>
```

### Fase 4: Integración con Bagisto

```php
// packages/Webkul/MuroLoco/src/Http/Controllers/WebhookController.php
<?php

namespace Webkul\MuroLoco\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\CartRule\Repositories\CartRuleRepository;

class WebhookController extends Controller
{
    protected $productRepository;
    protected $cartRuleRepository;

    public function __construct(
        ProductRepository $productRepository,
        CartRuleRepository $cartRuleRepository
    ) {
        $this->productRepository = $productRepository;
        $this->cartRuleRepository = $cartRuleRepository;
    }

    /**
     * Send product to Muro Loco when created/updated
     */
    public function syncProduct(Request $request, $productId)
    {
        $product = $this->productRepository->findOrFail($productId);

        // Check if product has active promotion
        $promotion = $this->getActivePromotion($product);

        if (!$promotion) {
            return response()->json(['message' => 'No active promotion'], 400);
        }

        // Prepare data for WoWonder
        $data = [
            'product_id' => $product->id,
            'title' => $product->name,
            'description' => $product->short_description,
            'media_url' => $product->images[0]->url ?? '',
            'media_type' => 'image',
            'price_original' => $product->price,
            'price_discount' => $promotion['discounted_price'],
            'discount_percentage' => $promotion['discount_percentage'],
            'coupon_code' => $promotion['coupon_code'],
            'coupon_expires_at' => $promotion['expires_at'],
            'bagisto_product_url' => route('shop.product.index', $product->url_key),
            'category_id' => $product->categories[0]->id ?? null,
        ];

        // Send to WoWonder API
        $wowonderUrl = env('WOWONDER_API_URL') . '/api/muro-loco/create-post';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('WOWONDER_API_TOKEN'),
        ])->post($wowonderUrl, $data);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Product synced to Muro Loco',
                'muro_loco_post_id' => $response->json()['post_id']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to sync to Muro Loco',
            'error' => $response->body()
        ], 500);
    }

    /**
     * Get active promotion for product
     */
    protected function getActivePromotion($product)
    {
        $cartRules = $this->cartRuleRepository->getModel()
            ->where('status', 1)
            ->where('starts_from', '<=', now())
            ->where('ends_till', '>=', now())
            ->get();

        foreach ($cartRules as $rule) {
            // Check if rule applies to this product
            if ($this->ruleAppliesToProduct($rule, $product)) {
                $discountedPrice = $this->calculateDiscountedPrice($product->price, $rule);

                return [
                    'coupon_code' => $rule->coupon_code,
                    'discounted_price' => $discountedPrice,
                    'discount_percentage' => $rule->discount_amount,
                    'expires_at' => $rule->ends_till,
                ];
            }
        }

        return null;
    }

    protected function ruleAppliesToProduct($rule, $product)
    {
        // Implementation depends on Bagisto's cart rule logic
        return true; // Simplified
    }

    protected function calculateDiscountedPrice($originalPrice, $rule)
    {
        if ($rule->action_type === 'by_percent') {
            return $originalPrice * (1 - $rule->discount_amount / 100);
        }

        return $originalPrice - $rule->discount_amount;
    }
}
```

```php
// packages/Webkul/MuroLoco/src/Providers/EventServiceProvider.php
<?php

namespace Webkul\MuroLoco\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\Product\Events\ProductCreated;
use Webkul\Product\Events\ProductUpdated;
use Webkul\MuroLoco\Listeners\SyncProductToMuroLoco;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ProductCreated::class => [
            SyncProductToMuroLoco::class,
        ],
        ProductUpdated::class => [
            SyncProductToMuroLoco::class,
        ],
    ];
}
```

### Fase 5: Admin Panel (Bagisto)

```php
// Admin puede marcar productos para Muro Loco
// packages/Webkul/Admin/src/Resources/views/catalog/products/edit.blade.php

<div class="control-group">
    <label>{{ __('admin::app.catalog.products.publish-to-muro-loco') }}</label>

    <input
        type="checkbox"
        name="publish_to_muro_loco"
        value="1"
        {{ $product->publish_to_muro_loco ? 'checked' : '' }}
    />

    <span class="help">Publicar automáticamente en Muro Loco cuando haya promoción activa</span>
</div>

<div class="control-group" id="muro-loco-preview">
    <label>Vista Previa Muro Loco</label>

    <div class="muro-loco-card-preview">
        <img src="{{ $product->images[0]->url }}" />
        <h3>{{ $product->name }}</h3>
        <div class="price">
            <span class="original">${{ $product->price }}</span>
            <span class="discount" id="preview-discount-price">$XX.XX</span>
        </div>
        <div class="coupon">
            Cupón: <strong id="preview-coupon-code">XXXXX</strong>
        </div>
    </div>
</div>
```

---

## 📈 Métricas y Analytics

### Dashboards a Implementar

**Para Admin (Bagisto):**
```
┌─────────────────────────────────────────┐
│  Muro Loco Analytics Dashboard          │
├─────────────────────────────────────────┤
│                                         │
│  📊 Métricas Generales                  │
│  • Posts publicados: 245                │
│  • Vistas totales: 125,340              │
│  • Clicks a productos: 12,534           │
│  • Conversión: 4.2%                     │
│                                         │
│  🏆 Top Productos                       │
│  1. iPhone 15 Pro - 2,345 clicks        │
│  2. Nike Air Max - 1,890 clicks         │
│  3. Laptop Dell - 1,654 clicks          │
│                                         │
│  💰 Cupones Más Usados                  │
│  1. RAM30 - 567 usos                    │
│  2. TECH20 - 432 usos                   │
│  3. FLASH50 - 389 usos                  │
│                                         │
│  📈 Tendencias                          │
│  [Gráfico de vistas por día]            │
│                                         │
└─────────────────────────────────────────┘
```

### Queries para Analytics

```sql
-- Vista más completa para analytics
CREATE VIEW muro_loco_analytics AS
SELECT
    p.id,
    p.title,
    p.product_id,
    p.views_count,
    p.likes_count,
    p.comments_count,
    p.clicks_count,
    ROUND((p.clicks_count / p.views_count * 100), 2) as ctr_percentage,
    COUNT(DISTINCT ml.user_id) as unique_likes,
    COUNT(DISTINCT mc.user_id) as unique_commenters,
    p.published_at,
    DATEDIFF(NOW(), p.published_at) as days_active
FROM muro_loco_posts p
LEFT JOIN muro_loco_likes ml ON p.id = ml.post_id
LEFT JOIN muro_loco_comments mc ON p.id = mc.post_id
GROUP BY p.id;

-- Top productos por conversión
SELECT
    p.title,
    p.clicks_count,
    COUNT(o.id) as orders_count,
    ROUND((COUNT(o.id) / p.clicks_count * 100), 2) as conversion_rate
FROM muro_loco_posts p
LEFT JOIN muro_loco_clicks mc ON p.id = mc.post_id
LEFT JOIN orders o ON o.customer_id = mc.user_id
    AND o.created_at >= mc.clicked_at
    AND o.created_at <= DATE_ADD(mc.clicked_at, INTERVAL 24 HOUR)
WHERE p.published_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY p.id
ORDER BY conversion_rate DESC
LIMIT 10;
```

---

## 📱 Mobile App Considerations

### Progressive Web App (PWA)

Convertir Muro Loco en PWA para experiencia app-like:

```javascript
// sw.js - Service Worker
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open('muro-loco-v1').then((cache) => {
      return cache.addAll([
        '/muro-loco/',
        '/themes/default/css/muro-loco.css',
        '/themes/default/js/muro-loco.js',
      ]);
    })
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});
```

```json
// manifest.json
{
  "name": "Muro Loco - RAM Comercios",
  "short_name": "Muro Loco",
  "description": "Descubre ofertas increíbles",
  "start_url": "/muro-loco/",
  "display": "standalone",
  "background_color": "#667eea",
  "theme_color": "#667eea",
  "icons": [
    {
      "src": "/images/icon-192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "/images/icon-512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ]
}
```

---

## ⏱️ Timeline de Desarrollo

### Fase 1: Base (1 semana)
- ✅ Bagisto ya instalado
- Crear tablas de BD en WoWonder
- Setup de estructura básica

### Fase 2: Backend Muro Loco (2 semanas)
- APIs PHP en WoWonder
- Endpoints de feed, like, comment
- Sistema de tracking

### Fase 3: Frontend Muro Loco (2-3 semanas)
- UI estilo TikTok
- Scroll vertical infinito
- Interacciones (like, comment, share)
- Responsive design

### Fase 4: Integración Bagisto (1-2 semanas)
- Webhook system
- Auto-sync de productos
- Admin panel en Bagisto

### Fase 5: Testing & Optimización (1 semana)
- Testing de integración
- Performance optimization
- Bug fixes

### Fase 6: Analytics & Monitoring (1 semana)
- Dashboard de métricas
- Tracking de conversiones
- A/B testing setup

**TOTAL: 8-10 semanas**

---

## 💰 Estimación de Costos

### Desarrollo

| Fase | Horas | Costo ($50/hr) |
|------|-------|----------------|
| Base y BD | 40h | $2,000 |
| Backend APIs | 80h | $4,000 |
| Frontend UI | 100h | $5,000 |
| Integración Bagisto | 60h | $3,000 |
| Testing | 40h | $2,000 |
| Analytics | 40h | $2,000 |
| **TOTAL** | **360h** | **$18,000** |

### Infraestructura Mensual

| Servicio | Costo/mes |
|----------|-----------|
| Servidor (existente) | $0 |
| CDN para media | $50-100 |
| Monitoring tools | $0-50 |
| **TOTAL** | **$50-150/mes** |

---

## 🚀 Lanzamiento y Marketing

### Pre-Lanzamiento

1. **Beta Testing (2 semanas)**
   - Invitar a 50-100 usuarios beta
   - Recolectar feedback
   - Ajustar UX

2. **Promoción Interna**
   - Post en feed principal de WoWonder
   - Email blast a base de usuarios
   - Banner en homepage

### Lanzamiento

1. **Evento de Lanzamiento**
   - Cupones especiales solo en Muro Loco
   - Ofertas exclusivas primer día
   - Gamification (primeros 100 usuarios obtienen descuento extra)

2. **Marketing Continuo**
   - Push notifications de nuevas ofertas
   - Influencers en RAM promoviendo productos
   - Challenges y contests

---

## 🔐 Seguridad y Privacidad

### Consideraciones

1. **Rate Limiting**
   ```php
   // Limitar requests para prevenir spam
   $throttle = RateLimiter::for('muro-loco-api', function (Request $request) {
       return Limit::perMinute(60)->by($request->user()->id);
   });
   ```

2. **Validación de Contenido**
   - Moderación de comentarios
   - Filtro de spam
   - Verificación de productos

3. **Privacidad de Datos**
   - GDPR compliance
   - No compartir datos de compras sin consentimiento
   - Opción de ocultar actividad de Muro Loco

---

## 📚 Documentación para Desarrolladores

### Estructura de Archivos

```
WoWonder/
├── ajax/
│   └── muro-loco/
│       ├── get-feed.php
│       ├── like-post.php
│       ├── comment-post.php
│       ├── track-click.php
│       └── create-post.php
├── themes/
│   └── default/
│       ├── statics/
│       │   └── muro-loco.html
│       ├── css/
│       │   └── muro-loco.css
│       └── js/
│           └── muro-loco.js
└── includes/
    └── muro-loco/
        ├── MuroLocoFeed.php
        ├── MuroLocoPost.php
        └── MuroLocoAnalytics.php

Bagisto/
└── packages/
    └── Webkul/
        └── MuroLoco/
            ├── src/
            │   ├── Http/
            │   │   └── Controllers/
            │   │       └── WebhookController.php
            │   ├── Listeners/
            │   │   └── SyncProductToMuroLoco.php
            │   └── Providers/
            │       └── MuroLocoServiceProvider.php
            └── README.md
```

---

## 🎯 KPIs y Métricas de Éxito

### Métricas Principales

1. **Engagement**
   - Daily Active Users en Muro Loco
   - Tiempo promedio en feed
   - Scroll depth promedio

2. **Conversión**
   - CTR (Click-Through Rate): >5%
   - Conversion Rate: >3%
   - AOV (Average Order Value) desde Muro Loco

3. **Contenido**
   - Productos publicados por semana
   - Cupones utilizados
   - Posts más virales

### Metas (3 meses post-lanzamiento)

- 40% de usuarios de RAM visitan Muro Loco semanalmente
- 10% de ventas de Bagisto vienen de Muro Loco
- 25% de cupones se descubren vía Muro Loco
- 1,000+ likes promedio por producto destacado

---

## 🔄 Roadmap Futuro

### v1.0 - MVP (3 meses)
- ✅ Feed básico de productos
- ✅ Sistema de likes y comentarios
- ✅ Integración con Bagisto
- ✅ Cupones digitales

### v1.1 - Mejoras (6 meses)
- Video posts (productos en acción)
- Stories de productos (24h)
- Live shopping events
- AR try-on (ropa/accesorios)

### v1.2 - Social Commerce (9 meses)
- Compra directa en Muro Loco (sin salir a Bagisto)
- Checkout en modal
- Apple Pay / Google Pay
- Wishlist compartida

### v2.0 - Marketplace Social (12 meses)
- Vendors pueden publicar directamente
- Influencer partnerships
- Affiliate system
- UGC (User Generated Content) de productos

---

## 📞 Contacto y Soporte

Para implementación de este plan, contactar al equipo de desarrollo:

- **Documentación:** Ver `devops/docs/`
- **Código:** Ver `devops/docker/`
- **Scripts:** Ver `devops/scripts/`

---

**Última actualización:** 2025-12-10
**Versión del plan:** 1.0
**Estado:** Listo para implementación
