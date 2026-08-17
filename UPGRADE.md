# UPGRADE Guide

- [Upgrading To v2.5 From v2.4](#upgrading-to-v25-from-v24)

## High Impact Changes

- [Laravel 13 and PHP 8.4](#laravel-13-and-php-84)
- [Search Architecture Refactored to Engine-Agnostic Design](#search-architecture-refactored-to-engine-agnostic-design)
- [Tailwind CSS Upgraded from v3 to v4](#tailwind-css-upgraded-from-v3-to-v4)

## Medium Impact Changes

- [Image Processing Moved to Laravel's Image Component](#image-processing-moved-to-laravels-image-component)
- [PostgreSQL Support](#postgresql-support)

## Upgrading To v2.5 From v2.4

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Laravel 13 and PHP 8.4

**Impact Probability: High**

Bagisto v2.5 runs on Laravel 13 and requires **PHP 8.4 or newer**. This is the first thing to deal with, because nothing else installs until the runtime is right.

```diff
- "php": ">=8.3 <8.5",
- "laravel/framework": "^12.0",
+ "php": "^8.4",
+ "laravel/framework": "^13.0",
```

Upgrade PHP before running `composer install`; on 8.3 the install aborts rather than degrading, because part of the dependency tree now requires 8.4.

#### Dependencies that moved with it

| Package | v2.4 | v2.5 |
|---------|------|------|
| `laravel/framework` | `^12.0` | `^13.0` |
| `laravel/tinker` | `^2.10` | `^3.0` |
| `kalnoy/nestedset` | `^6.0` | `^7.0` |
| `prettus/l5-repository` | `^2.6` | `^4.0` |
| `konekt/concord` | `^1.16` | `^1.18` |
| `spatie/laravel-responsecache` | `^7.4` | `^8.4` |
| `spatie/laravel-sitemap` | `^7.3` | `^8.0` |
| `pestphp/pest` | `^3.0` | `^4.0` |
| `phpunit/phpunit` | `^11.0` | `^12.0` |
| `barryvdh/laravel-debugbar` | `^3.8` | `^4.3` |

If you depend on any of these directly, check their own upgrade notes — the majors here are not drop-in.

#### PHP 8.4: implicitly nullable parameters

PHP 8.4 deprecates an implicitly nullable parameter — a typed parameter defaulting to `null` without the type being nullable. Bagisto's own code is clean, but custom packages often are not:

```diff
- public function handle(Product $product, string $locale = null)
+ public function handle(Product $product, ?string $locale = null)
```

#### Entry-point stubs

`artisan` and `public/index.php` had stayed on the pre-Laravel-11 shape and are now the current ones. If you have not customised them, copy the versions from this release. If you have, the change is that the HTTP and console kernels are no longer resolved by hand:

```diff
- $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
- $status = $kernel->handle($input = new ArgvInput, new ConsoleOutput);
- $kernel->terminate($input, $status);
+ $status = $app->handleCommand(new ArgvInput);
```

#### Configuration

The shipped config files were brought up to the Laravel 13 shape. Four keys Laravel renamed are now spelled the current way — the old names still work through fallbacks, so this is tidying rather than a break:

| Old key | New key |
|---------|---------|
| `logging.channels.daily.days` | `max_files` |
| `logging.channels.stderr.with` | `handler_with` |
| `mail.mailers.smtp.encryption` | `scheme` |
| `services.postmark.token` | `key` |

Options Laravel 13 added are now present rather than implied: the `deferred`, `background` and `failover` queue connections, the `storage` and `failover` cache stores, redis retry backoff, a `monthly` log channel, and `session.serialization`.

> [!IMPORTANT]
> `session.serialization` is set to `php`, not the `json` that a fresh Laravel 13 application defaults to. `json` is the safer choice — it closes off gadget-chain attacks if `APP_KEY` leaks — but it cannot read sessions written the old way. Switch it once you can accept every session being invalidated.

If you maintain your own copies of these files, mirror the same changes.

---

### Image Processing Moved to Laravel's Image Component

**Impact Probability: Medium**

Laravel 13 ships its own image component, so Bagisto no longer maintains a wrapper around Intervention Image. Code that resizes or re-encodes images now uses `Illuminate\Image`.

> [!NOTE]
> Intervention Image has **not** gone away — Laravel's `gd` and `imagick` drivers are built on it, so it remains a dependency and is now required at `^4.0`. What changed is that Bagisto no longer writes against its API.

#### Removed Classes

These were part of `webkul/imagecache` and were unreachable from the application — nothing resolved or instantiated them. They modelled Intervention's API (`brightness()`, `gamma()`, `colorize()`, `pixelate()`, `pad()`), which Laravel's component does not provide, so they were removed rather than rewritten:

| Removed | Replacement |
|---------|-------------|
| `Webkul\ImageCache\ImageCache` | None. Use `Illuminate\Image\ImageManager` (`image_manager()` or the `Image` facade). |
| `Webkul\ImageCache\CachedImage` | `Illuminate\Image\Image` |
| `Webkul\ImageCache\HashableClosure` | None. |

If your package referenced any of them, move to Laravel's component.

#### API Changes

`image_manager()` still exists, but now returns `Illuminate\Image\ImageManager` rather than Intervention's:

```diff
- image_manager()->read($uploadedFile)->encodeByExtension('webp');
+ image_manager()->fromUpload($uploadedFile)->toWebp()->toBytes();

- image_manager()->read($path)->encodeByMediaType();
+ image_manager()->fromPath($path)->toBytes();
```

Reading is now explicit about its source — `fromPath()`, `fromUpload()`, `fromBytes()`, `fromUrl()`, `fromStorage()` — instead of one `read()` that guessed. Encoding is `toWebp()`, `toJpeg()`, `toPng()` and so on, followed by `toBytes()`.

Cache filters and templates type-hint the new image class:

```diff
- use Intervention\Image\Interfaces\ImageInterface;
+ use Illuminate\Image\Image;

- public function applyFilter(ImageInterface $image): ImageInterface
+ public function applyFilter(Image $image): Image
```

`cover()`, `contain()`, `crop()`, `resize()`, `scale()`, `rotate()`, `blur()`, `sharpen()`, `grayscale()` and the flips carry the same names, so filter bodies usually need no change.

#### Configuration

The driver setting moved to the file the framework reads, and the key changed:

```diff
- // config/image.php
- 'driver' => 'gd',

+ // config/images.php
+ 'default' => env('IMAGE_DRIVER', 'gd'),
```

Delete `config/image.php` and add `config/images.php`. Supported drivers are still `gd` and `imagick`.

#### Composer

```diff
- "intervention/image": "^2.4|^3.0",
+ "intervention/image": "^4.2",
```

The `dont-discover` entry for `intervention/image` can go — v4 ships no Laravel service provider to discover.

---

### PostgreSQL Support

**Impact Probability: Medium**

Bagisto now runs on PostgreSQL 16 as well as MySQL 8.0, and CI covers both. Existing MySQL installations are unaffected and need no action.

Custom code, however, has to survive on either engine. Two rules cover most of it:

```diff
// LIKE is case-insensitive on MySQL and case-sensitive on PostgreSQL
- ->where('name', 'like', "%{$term}%")
+ ->where('name', db_grammar()->caseInsensitiveLike(), "%{$term}%")
```

```diff
// MySQL coerces "" to 0/NULL; PostgreSQL rejects it outright
+ public function setSpecialPriceAttribute($value)
+ {
+     $this->attributes['special_price'] = $value === '' ? null : $value;
+ }
```

Beyond those: every non-aggregated column in a `SELECT` must appear in `GROUP BY`, `CAST(... AS CHAR)` truncates to one character so use `VARCHAR(255)`, and `DB::raw()` inside `updateOrCreate()` fails on insert. Boolean columns need a `'boolean'` cast to come back consistently from both drivers.

The production Docker images ship in both flavours — see [`docker/production/README.md`](docker/production/README.md).

---

### Search Architecture Refactored to Engine-Agnostic Design

**Impact Probability: High**

Bagisto v2.5 replaces the tightly-coupled Elasticsearch search infrastructure in the `Product` package with an engine-agnostic design using the Strategy and Manager patterns. This enables swapping search engines (e.g., Algolia, Pinecone) without modifying core code.

#### Removed Classes

The following classes have been **deleted**:

| Removed | Replacement |
|---------|-------------|
| `Webkul\Product\Repositories\ElasticSearchRepository` | `Webkul\Product\Services\Search\Engines\ElasticSearchEngine` |
| `Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex` | `Webkul\Product\Jobs\Search\IndexProducts` |
| `Webkul\Product\Jobs\ElasticSearch\DeleteIndex` | `Webkul\Product\Jobs\Search\DeleteProducts` |
| `Webkul\Product\Helpers\Product` | See "Renamed Methods" below |

If your custom code imports any of these classes, update the imports to their replacements.

#### Removed Methods

| Removed | Replacement |
|---------|-------------|
| `ProductRepository::setSearchEngine(string)` | `ProductRepository::setSearchContext(SearchContextEnum)` |

#### Renamed Methods

| Old | New |
|-----|-----|
| `Product::formatElasticSearchIndexName(channel, locale)` | `ElasticSearchEngine::formatIndexName(channel, locale)` |

The `Webkul\Product\Helpers\Product` class has been deleted. Its only method moved to `ElasticSearchEngine`:

```diff
- use Webkul\Product\Helpers\Product;
- Product::formatElasticSearchIndexName($channelCode, $localeCode);
+ use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;
+ ElasticSearchEngine::formatIndexName($channelCode, $localeCode);
```

#### New Enums

Two enums replace all hardcoded search-related strings:

**`Webkul\Product\Enums\SearchEngineEnum`** — search driver values:
- `SearchEngineEnum::DATABASE` (`'database'`)
- `SearchEngineEnum::ELASTIC` (`'elastic'`)

**`Webkul\Product\Enums\SearchContextEnum`** — search context values:
- `SearchContextEnum::STOREFRONT` (`'storefront'`)
- `SearchContextEnum::ADMIN` (`'admin'`)

#### New Contracts

Two contracts define the engine abstraction:

- `Webkul\Product\Contracts\SearchEngine` — search operations (`search`, `getSuggestions`, `getMaxPrice`, `findBySlug`)
- `Webkul\Product\Contracts\SearchIndexer` — indexing operations (`indexBatch`, `deleteBatch`, `reindexFull`)

#### New Services

| Class | Purpose |
|-------|---------|
| `Services\Search\SearchEngineManager` | Centralizes all config resolution — single source of truth for which engine/driver is active |
| `Services\Search\Engines\DatabaseEngine` | `SearchEngine` implementation for database mode |
| `Services\Search\Engines\ElasticSearchEngine` | `SearchEngine` implementation for Elasticsearch |
| `Services\Search\Indexers\ElasticSearchIndexer` | `SearchIndexer` implementation wrapping the existing `Helpers\Indexers\ElasticSearch` |
| `Services\Search\Indexers\NullIndexer` | No-op `SearchIndexer` for database mode (eliminates config guards) |

#### Migration Steps

1. **Update search engine config checks:**

   All scattered `core()->getConfigData('catalog.products.search.engine')` checks are now centralized in `SearchEngineManager`. If your custom code checks the search engine config directly, use the manager instead:

   ```diff
   - if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
   -     $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
   - }
   - $this->productRepository->setSearchEngine($searchEngine ?? 'database');
   + use Webkul\Product\Enums\SearchContextEnum;
   + $this->productRepository->setSearchContext(SearchContextEnum::STOREFRONT);
   ```

   For admin context:

   ```diff
   - if (
   -     core()->getConfigData('catalog.products.search.engine') == 'elastic'
   -     && core()->getConfigData('catalog.products.search.admin_mode') == 'elastic'
   - ) {
   -     $searchEngine = 'elastic';
   - }
   - $this->productRepository->setSearchEngine($searchEngine ?? 'database');
   + use Webkul\Product\Enums\SearchContextEnum;
   + $this->productRepository->setSearchContext(SearchContextEnum::ADMIN);
   ```

2. **Update job dispatches:**

   ```diff
   - use Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex;
   - use Webkul\Product\Jobs\ElasticSearch\DeleteIndex;
   + use Webkul\Product\Jobs\Search\IndexProducts;
   + use Webkul\Product\Jobs\Search\DeleteProducts;

   - UpdateCreateIndex::dispatch($productIds);
   + IndexProducts::dispatch($productIds);

   - DeleteIndex::dispatch($productIds);
   + DeleteProducts::dispatch($productIds);
   ```

   The new jobs use `SearchEngineManager` internally. When the master engine is `database`, the `NullIndexer` handles the call as a no-op — no config guards needed in your code.

3. **Update `ElasticSearchRepository` usage:**

   ```diff
   - use Webkul\Product\Repositories\ElasticSearchRepository;
   + use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;
   ```

   If you were calling `ElasticSearchRepository` methods directly, use `SearchEngineManager::engine()` instead:

   ```diff
   - $results = $this->elasticSearchRepository->search(...)
   + $engine = app(SearchEngineManager::class)->engine(SearchContextEnum::STOREFRONT);
   + $results = $engine->search($params, $options);
   ```

4. **Update Artisan indexer commands:**

   The `indexer:index` command now uses `search` instead of `elastic` as the type flag:

   ```diff
   - php artisan indexer:index --type=elastic --mode=full
   + php artisan indexer:index --type=search --mode=full
   ```

5. **Update `Product::formatElasticSearchIndexName()` calls:**

   ```diff
   - use Webkul\Product\Helpers\Product;
   - $index = Product::formatElasticSearchIndexName($channelCode, $localeCode);
   + use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;
   + $index = ElasticSearchEngine::formatIndexName($channelCode, $localeCode);
   ```

6. **Update direct `ElasticSearch` facade usage in DataGrids:**

   If your custom DataGrid calls the `ElasticSearch` facade directly, route it through `ElasticSearchEngine::rawSearch()` instead:

   ```diff
   - use Webkul\Core\Facades\ElasticSearch;
   + use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;

   - $results = ElasticSearch::search([
   + $engine = app(ElasticSearchEngine::class);
   + $results = $engine->rawSearch([
         'index' => $indexNames,
         'body'  => [ /* your query body */ ],
     ]);
   ```

   Use `SearchEngineManager::resolveDriver()` for the config check instead of reading config directly:

   ```diff
   - if (
   -     core()->getConfigData('catalog.products.search.engine') != 'elastic'
   -     || core()->getConfigData('catalog.products.search.admin_mode') != 'elastic'
   - ) {
   + use Webkul\Product\Enums\SearchContextEnum;
   + use Webkul\Product\Enums\SearchEngineEnum;
   + use Webkul\Product\Services\Search\SearchEngineManager;
   +
   + $manager = app(SearchEngineManager::class);
   + if ($manager->resolveDriver(SearchContextEnum::ADMIN) === SearchEngineEnum::DATABASE) {
         parent::processRequest();
         return;
     }
   ```

7. **Update `SearchEngineManager` usage for config checks:**

   If you need to check whether an external engine is enabled:

   ```php
   use Webkul\Product\Services\Search\SearchEngineManager;

   $manager = app(SearchEngineManager::class);

   // Check if external engine is configured
   if ($manager->isExternalEngineEnabled()) {
       // ...
   }

   // Get the resolved driver for a context
   use Webkul\Product\Enums\SearchContextEnum;
   use Webkul\Product\Enums\SearchEngineEnum;

   $driver = $manager->resolveDriver(SearchContextEnum::STOREFRONT);
   if ($driver === SearchEngineEnum::ELASTIC) {
       // ...
   }
   ```

#### Adding a Custom Search Engine

To add a new search engine (e.g., Algolia):

1. Implement `Webkul\Product\Contracts\SearchEngine`:

   ```php
   class AlgoliaEngine implements SearchEngine
   {
       public function search(array $params, array $options): array { /* ... */ }
       public function getSuggestions(?string $query): ?string { /* ... */ }
       public function getMaxPrice(array $params = []): float { /* ... */ }
       public function findBySlug(string $slug): ?int { /* ... */ }
   }
   ```

2. Implement `Webkul\Product\Contracts\SearchIndexer`:

   ```php
   class AlgoliaIndexer implements SearchIndexer
   {
       public function indexBatch(array $products): void { /* ... */ }
       public function deleteBatch(array $productIds): void { /* ... */ }
       public function reindexFull(): void { /* ... */ }
   }
   ```

3. Add a case to `SearchEngineEnum`:

   ```php
   case ALGOLIA = 'algolia';
   ```

4. Register named bindings in your service provider:

   ```php
   $this->app->singleton('product.search.engine.algolia', AlgoliaEngine::class);
   $this->app->singleton('product.search.indexer.algolia', AlgoliaIndexer::class);
   ```

The `SearchEngineManager` will automatically resolve your engine when the config value matches the enum case.

### Tailwind CSS Upgraded from v3 to v4

**Impact Probability: High**

Bagisto v2.5 upgrades the `Admin`, `Shop`, and `Installer` frontend stacks from Tailwind CSS v3 to v4. v4 is a ground-up rewrite that replaces the PostCSS pipeline with an official Vite plugin, moves configuration from JavaScript to CSS, and renames several utility classes. Any custom theme, extension package, or Blade override that ships its own Tailwind classes will need updates.

Official upgrade reference: https://tailwindcss.com/docs/upgrade-guide.

#### Removed Files

The following files have been **deleted** from each of the three frontend packages:

| Removed | Reason |
|---------|--------|
| `packages/Webkul/Admin/tailwind.config.js` | v4 uses CSS-first configuration (`@theme`, `@utility`, `@custom-variant` in `app.css`). |
| `packages/Webkul/Shop/tailwind.config.js` | Same as above. |
| `packages/Webkul/Installer/tailwind.config.js` | Same as above. |
| `packages/Webkul/Admin/postcss.config.cjs` | `@tailwindcss/vite` replaces the PostCSS pipeline; no PostCSS config is needed. |
| `packages/Webkul/Shop/postcss.config.cjs` | Same as above. |
| `packages/Webkul/Installer/postcss.config.cjs` | Same as above. |

If your custom package copied any of these files, delete them and follow the CSS-first pattern described below.

#### Package Dependency Changes

Each of `Admin`, `Shop`, and `Installer` had their `package.json` updated:

```diff
  "devDependencies": {
-   "autoprefixer": "^10.4.16",
+   "@tailwindcss/vite": "^4.0.0",
-   "postcss": "^8.4.23",
-   "tailwindcss": "^3.3.2",
+   "tailwindcss": "^4.0.0",
    // ...
  }
```

`autoprefixer` and `postcss` are no longer required — the Vite plugin runs Lightning CSS internally and handles vendor prefixing automatically.

If your custom package extended Bagisto's frontend build, mirror the same dependency change and delete your own `postcss.config.*` file.

#### Vite Configuration Changes

Each `vite.config.js` now registers the Tailwind Vite plugin:

```diff
  import { defineConfig, loadEnv } from "vite";
  import vue from "@vitejs/plugin-vue";
  import laravel from "laravel-vite-plugin";
+ import tailwindcss from "@tailwindcss/vite";
  import path from "path";

  export default defineConfig(({ mode }) => {
      // ...
      return {
          // ...
          plugins: [
              vue(),
+             tailwindcss(),
              laravel({ /* ... */ }),
          ],
      };
  });
```

#### `app.css` Configuration Migration

The `@tailwind` directives are replaced with a single `@import`, and every option that previously lived in `tailwind.config.js` (breakpoints, colors, fonts, container, dark mode, content paths) is moved into CSS. Example for `packages/Webkul/Admin/src/Resources/assets/css/app.css`:

```diff
- @tailwind base;
- @tailwind components;
- @tailwind utilities;
+ @import "tailwindcss" source("../../");
+
+ @custom-variant dark (&:where(.dark, .dark *));
+
+ @theme {
+     --breakpoint-sm: 525px;
+     --breakpoint-md: 768px;
+     --breakpoint-lg: 1024px;
+     --breakpoint-xl: 1240px;
+     --breakpoint-2xl: 1920px;
+
+     --color-darkGreen: #40994A;
+     --color-darkBlue: #0044F2;
+     --color-darkPink: #F85156;
+
+     --font-inter: Inter;
+     --font-icon: icomoon;
+ }
+
+ @utility container {
+     margin-inline: auto;
+     padding-inline: 16px;
+
+     @variant 2xl {
+         max-width: 1920px;
+     }
+ }
```

Every configuration key that lived in v3's `tailwind.config.js` must now be expressed in CSS. The full mapping:

| v3 `tailwind.config.js` | v4 `app.css` |
|-------------------------|--------------|
| `theme.screens.*` | `--breakpoint-*` inside `@theme` |
| `theme.extend.colors.*` | `--color-*` inside `@theme` |
| `theme.extend.fontFamily.*` | `--font-*` inside `@theme` |
| `theme.extend.fontSize.*` | `--text-*` (plus optional `--text-*--line-height`) inside `@theme` |
| `theme.extend.spacing.*` | `--spacing-*` inside `@theme` (or override the base `--spacing` scale) |
| `theme.extend.borderRadius.*` | `--radius-*` inside `@theme` |
| `theme.extend.boxShadow.*` | `--shadow-*` inside `@theme` |
| `theme.extend.zIndex.*` | Removed — use arbitrary `z-<n>` (v4 accepts any integer natively) |
| `theme.container.{center, padding, screens}` | `@utility container { ... @variant <bp> { max-width: ... } }` |
| `darkMode: 'class'` | `@custom-variant dark (&:where(.dark, .dark *));` |
| `content: [...]` | `@import "tailwindcss" source("<relative-path>");` or `@source "..."` |
| `safelist: [{ pattern: /.../ }]` | `@source inline("class-a class-b ...");` |
| `plugins: [require('...')]` | `@plugin "...";` |
| `prefix: 'tw-'` | `@import "tailwindcss" prefix(tw);` |

#### Content and Safelist Path Behavior Changed

In v3, `content: ["./src/Resources/**/*.blade.php"]` explicitly listed every glob. In v4, source detection is automatic but rooted in a **base source directory** you declare:

- `@import "tailwindcss" source("../../");` — sets the base to `src/Resources/` (relative to `app.css`). Tailwind then auto-detects `.blade.php`, `.js`, `.vue`, and other recognized file types under that path.
- `@source "../../../SomePackage/src/Resources/**/*.blade.php";` — adds a specific glob outside the base directory.
- `@source inline("icon-a icon-b icon-c");` — the v4 replacement for v3's `safelist` regex, since regex patterns are no longer supported. Enumerate literal class names.

**Breaking behavior:** v3's `safelist` accepted regex patterns like `{ pattern: /icon-/ }`. v4's `@source inline(...)` does **not** support regex. If your custom package relied on a regex safelist, you must either enumerate every class explicitly, or move the class definitions out of `@layer components` so they are not tree-shaken.

#### Icon Classes Renamed and Restructured

The icon fonts moved from plain classes inside `@layer components` to Tailwind utilities. Each glyph is declared once in `@theme` and emitted by a single functional utility:

```diff
- @layer components {
-     .icon-cart:before { content: "\e90c"; }
- }
+ @theme {
+     --icon-cart: "\e90c";
+ }
+
+ @utility icon-* {
+     &::before { content: --value(--icon-*); }
+ }
```

They remain utilities rather than plain classes on purpose — the admin sets icons through variants such as `peer-checked:icon-checked` and `rtl:icon-sort-left`, which a plain class cannot carry.

**Renamed icons.** Icons named after a number rather than what they depict were renamed. If your theme or extension uses the old names, update them:

| Package | Old | New | Glyph |
|---------|-----|-----|-------|
| Admin | `icon-cancel-1` | `icon-close` | plain ✕ (`icon-cancel` remains the circled ✕) |
| Admin | `icon-customer-2` | `icon-customer` | person |
| Admin | `icon-checkbox-partical` | `icon-checkbox-partial` | spelling fix |
| Shop | `icon-filter-1` | `icon-funnel` | funnel (`icon-filter` remains the sliders) |
| Shop | `icon-compare-1` | `icon-swap` | curved crossing arrows |
| Shop | `icon-sort-1` | `icon-sort` | descending lines |

**Removed icons.** Glyphs that no core view referenced were dropped from the stylesheets so they no longer ship as dead CSS — 11 from Admin, 15 from Shop, 2 from Installer:

| Package | Removed |
|---------|---------|
| Admin | `icon-add-customer`, `icon-ar`, `icon-clip`, `icon-dots`, `icon-edit-save`, `icon-order-back`, `icon-product-1`, `icon-refund`, `icon-setting`, `icon-tick`, `icon-zoom` |
| Shop | `icon-Free-Shipping`, `icon-add-new`, `icon-astreisk`, `icon-box-fill`, `icon-camera-fill`, `icon-dislike`, `icon-email`, `icon-filter-fill`, `icon-heart-1`, `icon-heart-2`, `icon-left-arrow`, `icon-like`, `icon-right-arrow`, `icon-sort-by`, `icon-tick` |
| Installer | `icon-arrow-down`, `icon-view` |

The glyphs are still in the font files; if your package uses one, declare it in your own CSS:

```css
@theme {
    --icon-refund: "\e948";
}
```

**Safelisting.** The scan root now covers `src/` rather than `src/Resources/`, so class names written in `Config` and `DataGrids` files — menu and datagrid icons — are detected without help. Only names that no file in the package mentions still need `@source inline(...)`; in the storefront that is the five icons named by the SocialShare view and by the theme content the installer seeds.

#### Plugin Registration Changed

v3 registered plugins in JavaScript. v4 registers them in CSS via the `@plugin` directive:

```diff
- // tailwind.config.js
- module.exports = {
-     plugins: [
-         require('@tailwindcss/forms'),
-         require('@tailwindcss/typography'),
-     ],
- };
+ /* app.css */
+ @plugin "@tailwindcss/forms";
+ @plugin "@tailwindcss/typography";
```

Bagisto's core `Admin`, `Shop`, and `Installer` packages did not ship any Tailwind plugins in v3, so no `@plugin` directives are present. If your custom package registered plugins, migrate them to `@plugin` in your `app.css`.

#### v3 Compatibility Base Layer

v4 removed two implicit defaults that Bagisto relied on. To preserve v3 behavior, `app.css` now ships a `@layer base` block:

```css
@layer base {
    *,
    ::after,
    ::before,
    ::backdrop,
    ::file-selector-button {
        border-color: var(--color-gray-200, currentColor);
    }

    button:not(:disabled),
    [role="button"]:not(:disabled) {
        cursor: pointer;
    }
}
```

- **Default `border-color`**: v3 defaulted to `gray-200`; v4 defaults to `currentColor`. Without the rule above, every element with a bare `border` class would suddenly draw the text color.
- **Button cursor**: v3 set `cursor: pointer` on `<button>`; v4 does not. Without the rule above, bare `<button>` elements without `cursor-pointer` on them would show the arrow cursor.

If your custom theme overrides `app.css` from scratch, add this block or its equivalent.

#### `@apply` Inside `@keyframes` No Longer Supported

v4's Vite plugin errors out on `@apply` used inside `@keyframes` blocks. Rewrite to plain CSS:

```diff
  @keyframes skeleton {
      0% {
-         @apply bg-[-1250px_0];
+         background-position: -1250px 0;
      }

      100% {
-         @apply bg-[1250px_0];
+         background-position: 1250px 0;
      }
  }
```

If your custom `app.css` or theme file has similar `@apply` calls inside `@keyframes`, `@font-face`, or other non-selector blocks, inline the raw CSS.

#### Migration Steps

If you maintain a custom Bagisto theme, extension, or admin package with its own Tailwind assets, do the following:

1. **Update `package.json`** — remove `postcss`, `autoprefixer`, and `tailwindcss@^3`; add `tailwindcss@^4.0.0` and `@tailwindcss/vite@^4.0.0`.

2. **Update `vite.config.js`** — import `tailwindcss` from `@tailwindcss/vite` and add `tailwindcss()` to the `plugins` array.

3. **Delete `postcss.config.*` and `tailwind.config.js`** from your package.

4. **Rewrite `app.css`** — replace `@tailwind` directives with `@import "tailwindcss" source("../../");` and translate any `theme.extend.*` values from your old JS config into `@theme` blocks. If you overrode Bagisto's colors, breakpoints, or fonts, register them in `@theme` with `--color-*`, `--breakpoint-*`, `--font-*` variables.

5. **Rebuild frontend assets:**

   ```bash
   cd packages/Webkul/Admin && rm -rf node_modules package-lock.json && npm install && npm run build
   cd ../Shop     && rm -rf node_modules package-lock.json && npm install && npm run build
   cd ../Installer && rm -rf node_modules package-lock.json && npm install && npm run build
   ```

6. **Visually verify** the storefront, admin panel, and installer flow. Common regression spots after a v4 upgrade:

   - Missing icons — if custom icon classes drop out, safelist them with `@source inline("icon-name-a icon-name-b ...");` in your `app.css`.
   - Elements with bare `border` render the wrong color — confirm the base-layer compat rule (see the "v3 Compatibility Base Layer" section above) is present.
   - Custom buttons without a `cursor-pointer` class show the arrow cursor — confirm the same base-layer rule.

   For any additional utility-class-level breaking changes in your custom Blade templates, refer to the official [Tailwind CSS v4 upgrade guide](https://tailwindcss.com/docs/upgrade-guide).
