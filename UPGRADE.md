# UPGRADE Guide

- [Upgrading To v2.5 From v2.4](#upgrading-to-v25-from-v24)
  - [Before You Start](#before-you-start)
  - [The Upgrade Itself](#the-upgrade-itself)

## High Impact Changes

- [Laravel 13 and PHP 8.4](#laravel-13-and-php-84)
- [Configuration Files You Maintain Yourself](#configuration-files-you-maintain-yourself)
- [Search Architecture Refactored to Engine-Agnostic Design](#search-architecture-refactored-to-engine-agnostic-design)
- [Tailwind CSS Upgraded from v3 to v4](#tailwind-css-upgraded-from-v3-to-v4)

## Medium Impact Changes

- [Relocated Configuration Codes](#relocated-configuration-codes)
- [Theme Section Media Are Stored As Bare Paths](#theme-section-media-are-stored-as-bare-paths)
- [Image Processing Moved to Laravel's Image Component](#image-processing-moved-to-laravels-image-component)
- [Remote Storage Drivers](#remote-storage-drivers)
- [The Omnibus Package](#the-omnibus-package)
- [PostgreSQL Support](#postgresql-support)

## Upgrading To v2.5 From v2.4

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Before You Start

Bagisto is distributed as a full Laravel application rather than as a package inside one, so an upgrade means bringing your copy of the application files up to the new release and reconciling anything you changed. Nothing republishes them for you.

1. **Move to PHP 8.4.** Part of the dependency tree now requires it, so `composer install` aborts on 8.3 rather than resolving to older packages.

2. **Back up the database and `storage/`.** Four of this release's migrations rewrite existing rows in place — two rewrite JSON in `theme_section_translations`, two rename codes in `core_config` — and their `down()` methods reverse the shape, not a snapshot.

3. **Check that `APP_URL` names the site you are actually serving.** The `make_theme_section_urls_portable` migration decides which stored links belong to this store by comparing their host against `config('app.url')`. If `APP_URL` is wrong or still points at a development host when you migrate, links on the real domain are left as absolute URLs and keep breaking when the domain changes.

4. **Plan for maintenance mode.** The migrations rewrite configuration and theme content that requests read, so the store should be down for the duration. The sequence below opens with `php artisan down` and closes with `php artisan up`.

> [!WARNING]
> Do not run `php artisan bagisto:install` on an existing store. It is the fresh-install command and calls `db:wipe` followed by `migrate:fresh` — it will destroy your data. An upgrade only ever runs `php artisan migrate`.

### The Upgrade Itself

Start by bringing your application files up to the new release, reconciling anything you changed. The root files that carry changes are `composer.json`, `.env.example`, `artisan`, `public/index.php`, `bootstrap/app.php`, `bootstrap/providers.php`, `phpunit.xml` and `config/`. [Configuration Files You Maintain Yourself](#configuration-files-you-maintain-yourself) covers `config/` file by file.

Then run:

```bash
# Take the store down
php artisan down

# Install the new dependency tree
composer install

# Run the migrations
php artisan migrate --force

# Rebuild the Admin bundle
cd packages/Webkul/Admin && rm -rf node_modules package-lock.json && npm install && npm run build

# Rebuild the Shop bundle
cd ../Shop && rm -rf node_modules package-lock.json && npm install && npm run build

# Rebuild the Installer bundle
cd ../Installer && rm -rf node_modules package-lock.json && npm install && npm run build

# Clear every cached config, route, view and compiled service
php artisan optimize:clear

# Bring the store back up
php artisan up
```

Tailwind 4 changes the dependency set rather than extending it, which is why each bundle is rebuilt from a clean `node_modules` rather than installed over the old one.

If you run Elasticsearch, rebuild the product index afterwards — the flag name changed, see [Search Architecture Refactored to Engine-Agnostic Design](#search-architecture-refactored-to-engine-agnostic-design):

```bash
php artisan indexer:index --type=search --mode=full
```

Then verify:

```bash
php artisan bagisto:version
php artisan migrate:status
```

`bagisto:version` should report 2.5.x, and every migration should read `Ran`. Finally load the storefront home page, a category, a product page and the admin dashboard. The home page is worth loading first: it renders the theme sections whose stored paths the migrations rewrote.

---

### Laravel 13 and PHP 8.4

**Impact Probability: High**

Bagisto v2.5 runs on Laravel 13 and requires **PHP 8.4 or newer**.

```diff
- "php": ">=8.3 <8.5",
- "laravel/framework": "^12.0",
+ "php": "^8.4",
+ "laravel/framework": "^13.0",
```

#### Dependencies that moved with it

| Package | v2.4 | v2.5 |
|---------|------|------|
| `laravel/framework` | `^12.0` | `^13.0` |
| `laravel/tinker` | `^2.10` | `^3.0` |
| `laravel/ai` | `^0.2.2` | `^0.7.0` |
| `kalnoy/nestedset` | `^6.0` | `^7.0` |
| `prettus/l5-repository` | `^2.6` | `^4.0` |
| `konekt/concord` | `^1.16` | `^1.18` |
| `intervention/image` | `^2.4\|^3.0` | `^4.2` |
| `spatie/laravel-responsecache` | `^7.4` | `^8.4` |
| `spatie/laravel-sitemap` | `^7.3` | `^8.0` |
| `pestphp/pest` | `^3.0` | `^5.0` |
| `pestphp/pest-plugin-laravel` | `^3.0` | `^5.0` |
| `phpunit/phpunit` | `^11.0` | `^13.0` |
| `barryvdh/laravel-debugbar` | `^3.8` | `^4.3` |

One dependency is new: `league/flysystem-aws-s3-v3` (`^3.0`), which backs the S3 and Cloudflare R2 disks described under [Remote Storage Drivers](#remote-storage-drivers).

If you depend on any of these directly, check their own upgrade notes — the majors here are not drop-in. Bagisto's own Magic AI code needed no change for the `laravel/ai` bump, but that library is still pre-1.0, so custom code written against it should be re-checked.

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

#### Renamed names that still answer to the old ones

Laravel 13 renamed a handful of things Bagisto's skeleton files use. Every one of them keeps the old spelling working — as a config fallback, a deprecated method or a subclass — so these are tidying rather than a break. They are listed so a file you maintain yourself can be brought in line.

| Where | Old | New |
|---|---|---|
| `config/logging.php` | `channels.daily.days` | `max_files` |
| `config/logging.php` | `channels.stderr.with` | `handler_with` |
| `config/mail.php` | `mailers.smtp.encryption` | `scheme` |
| `config/services.php` | `postmark.token` | `key` |
| `bootstrap/app.php` | `$middleware->validateCsrfTokens(...)` | `$middleware->preventRequestForgery(...)` |
| `config/sanctum.php` | `Illuminate\Foundation\Http\Middleware\ValidateCsrfToken` | `...\PreventRequestForgery` |

Options Laravel 13 added are now present rather than implied: the `deferred`, `background` and `failover` queue connections, the `storage` and `failover` cache stores, redis retry backoff and `persistent`, a sqlite `transaction_mode`, a `monthly` log channel, `retry_after` on the failover and roundrobin mailers, and `session.serialization`.

> [!IMPORTANT]
> `session.serialization` is set to `php`, not the `json` that a fresh Laravel 13 application defaults to. `json` is the safer choice — it closes off gadget-chain attacks if `APP_KEY` leaks — but it cannot read sessions written the old way. Switch it once you can accept every session being invalidated.

---

### Configuration Files You Maintain Yourself

**Impact Probability: High**

`config/` ships as part of the application, so an upgrade does not touch it — your files stay exactly as they are. Most of the changes below are additive and cost you nothing if you skip them, but two are not: `config/responsecache.php` is a different shape now, and `config/image.php` no longer has a reader.

| File | What changed | If you skip it |
|---|---|---|
| `config/responsecache.php` | Restructured for responsecache v8 — see the key map below | **Full Page Cache breaks.** Bagisto reads `responsecache.cache.lifetime_in_seconds`, which does not exist in the old shape, so cached responses are given a lifetime of zero |
| `config/image.php` → `config/images.php` | The driver setting moved to the file the framework reads, and the key changed | **Image processing falls back to `gd`** whatever `config/image.php` says; nothing reads that file any more |
| `bootstrap/providers.php` | `OmnibusServiceProvider` added | The Omnibus package does not load |
| `config/concord.php` | `Webkul\Omnibus\Providers\ModuleServiceProvider` added | The Omnibus model is not registered |
| `config/cache.php` | `default` moved from `file` to `database`; `storage` and `failover` stores added; `serializable_classes` set | Nothing — your `.env` value still wins. See the note below before copying Laravel 13's own version of this file |
| `config/filesystems.php` | `r2` disk added, `report` keys added | The Cloudflare R2 driver has no disk to configure |
| `config/logging.php`, `config/mail.php`, `config/services.php`, `config/queue.php`, `config/session.php`, `config/database.php`, `config/sanctum.php` | The Laravel 13 renames and additions listed above | Nothing — the old names still resolve |
| `config/imagecache.php` | `cache_driver` removed | Nothing — the key is simply unread |
| `config/purify.php` | Serializer store default follows the new cache default | Nothing — your `.env` value still wins |
| `config/repository.php` | Comments only; every value is unchanged | Nothing. Do not bother replacing it |

#### `config/responsecache.php` key map

```diff
- 'cache_store'               => ...    + 'cache' => ['store'               => ...]
- 'cache_lifetime_in_seconds' => ...    + 'cache' => ['lifetime_in_seconds' => ...]
- 'cache_tag'                 => ...    + 'cache' => ['tag'                 => ...]
- 'cache_bypass_header.name'  => ...    + 'bypass' => ['header_name'        => ...]
- 'cache_bypass_header.value' => ...    + 'bypass' => ['header_value'       => ...]
- 'add_cache_time_header'     => ...    + 'debug'  => ['enabled'            => ...]
- 'cache_time_header_name'    => ...    + 'debug'  => ['cache_time_header_name' => ...]
- 'cache_age_header_name'     => ...    + 'debug'  => ['cache_age_header_name'  => ...]
- 'add_cache_age_header'      => ...    (removed — `debug.enabled` now gates every debug header)
- 'serializer' => DefaultSerializer::class
+ 'serializer' => JsonSerializer::class
```

v8 also adds `debug.cache_status_header_name`, `debug.cache_key_header_name` and an `ignored_query_parameters` list that keeps UTM, `gclid` and `fbclid` parameters from splitting the cache. Take the file from this release rather than patching yours key by key.

#### A note on `config/cache.php`

Bagisto sets `serializable_classes` to `true`. Laravel 13's own skeleton ships it as `false`, which blocks every object during unserialization. Bagisto's repository layer — `Webkul\Core\Eloquent\Repository`, mixed into roughly a hundred repositories — caches Eloquent models and collections, and `false` would hand them back as `__PHP_Incomplete_Class`. Omitting the key entirely behaves as it always did, so an untouched v2.4 `config/cache.php` is safe; the danger is copying Laravel 13's version in wholesale.

The shipped default for `CACHE_STORE` also moved from `file` to `database`, and a new root migration creates the `cache` and `cache_locks` tables `php artisan migrate` needs for it. An existing `.env` almost certainly pins `CACHE_STORE` already, in which case nothing changes.

#### Environment file

`.env.example` gained `AWS_URL`, `AWS_ENDPOINT` and the seven `R2_*` variables for the new storage drivers. None of them are required — the disks are configurable from the admin instead — and no variable was removed or renamed, so an existing `.env` needs no edit to keep working.

---

### Relocated Configuration Codes

**Impact Probability: Medium**

Admin → Configuration was reorganised, and two settings moved to the page they belong to. A migration carries the stored values across, so nothing is lost and no manual database work is needed:

| Before | After |
|---|---|
| `catalog.products.storefront.buy_now_button_display` | `catalog.products.product_view_page.buy_now_button_display` |
| `sales.checkout.my_cart.summary` | `sales.checkout.mini_cart.summary` |

Custom code that reads either code is not migrated for you:

```diff
- core()->getConfigData('sales.checkout.my_cart.summary')
+ core()->getConfigData('sales.checkout.mini_cart.summary')
```

The search settings moved too, and are covered in the search section below.

---

### Theme Section Media Are Stored As Bare Paths

**Impact Probability: Medium**

Theme sections used to record an upload as `storage/themes/...` and a link as a whole URL on the store's domain. Neither survived a change of domain or a move to a remote disk. Sections now store the bare path — `themes/...` — and resolve it when the page is rendered.

Two migrations convert what is already stored, live options and drafts alike:

| Migration | What it does |
|---|---|
| `2026_08_25_000001_drop_storage_prefix_from_theme_section_paths` | Strips the `storage/` prefix from paths under `themes/`, `theme/` and `section/` |
| `2026_08_25_000002_make_theme_section_urls_portable` | Reduces URLs on this store's own host to paths, and rewrites `src="storage/..."` inside authored HTML and CSS to `/storage/...` |

The second reads `config('app.url')` to decide which host is "this store", which is why `APP_URL` has to be right before you migrate.

#### If you override a section view

A view that built its own URLs from the stored value will now build them from a string that no longer contains `storage`. The storefront carousel is the clearest case:

```diff
- <img src="{{ $image['image'] }}"
-      srcset="{{ str_replace('storage', 'cache/large', $image['image']) }} 1280w, ...">
+ @php $resolved = bagisto_theme_storage()->imageUrls($image['image'] ?? null); @endphp
+ <img src="{{ $resolved['url'] }}"
+      srcset="{{ $resolved['srcset']['large'] }} 1280w, ...">
```

`bagisto_theme_storage()` returns `Webkul\Theme\ThemeStorage`, which resolves a stored path against whichever disk the store is on:

| Method | Returns |
|---|---|
| `url($path)` | The URL the original is served from |
| `resizedUrl($path, $size)` | The URL of one resized copy — `small`, `medium` or `large` |
| `imageUrls($path)` | `['url' => ..., 'srcset' => ['small' => ..., 'medium' => ..., 'large' => ...]]` |
| `embedUrl($path)` | The URL to write into authored markup, root-relative on a local disk |
| `normalize($path)` | The stored value reduced to a disk path, tolerating an old `storage/` prefix |

`bagisto_asset()` is unchanged and still resolves what a theme *ships*; `bagisto_theme_storage()` resolves what it *stores*.

---

### Image Processing Moved to Laravel's Image Component

**Impact Probability: Medium**

Laravel 13 ships its own image component, so Bagisto no longer maintains a wrapper around Intervention Image. Code that resizes or re-encodes images now uses `Illuminate\Image`.

> [!NOTE]
> Intervention Image has **not** gone away — Laravel's `gd` and `imagick` drivers are built on it, so it remains a dependency and is now required at `^4.2`. What changed is that Bagisto no longer writes against its API.

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

Reading is now explicit about its source — `fromPath()`, `fromUpload()`, `fromBytes()`, `fromUrl()`, `fromStorage()`, `fromStream()`, `fromBase64()` — instead of one `read()` that guessed. Encoding is `toWebp()`, `toJpeg()`, `toPng()` and so on, followed by `toBytes()`.

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

`config/imagecache.php` stays, minus its `cache_driver` key, which nothing read.

#### Composer

```diff
- "intervention/image": "^2.4|^3.0",
+ "intervention/image": "^4.2",
```

The `dont-discover` entry for `intervention/image` can go — v4 ships no Laravel service provider to discover.

---

### Remote Storage Drivers

**Impact Probability: Medium**

Amazon S3 and Cloudflare R2 can now be chosen at `Configuration → File Management`. The local disk remains the default and an upgraded store keeps using it, so this is opt-in.

Two things are worth knowing before you opt in:

- The chosen driver is applied at boot by `Webkul\Core\Filesystem\StorageConfigurator`, which sets `filesystems.default`. **Once a driver is recorded, it wins over `FILESYSTEM_DISK`.** With nothing recorded — the state every upgraded store is in — the environment is left alone.
- Nothing copies existing files. Switching the disk changes where *new* uploads go; the media already under `storage/app/public` has to be moved across yourself, or the store will serve broken images.

If you maintain your own `config/filesystems.php`, add the `r2` disk from this release. `league/flysystem-aws-s3-v3` arrives with `composer install` and backs both drivers; the enum that describes them refuses a driver whose adapter is missing rather than failing at upload time.

---

### The Omnibus Package

**Impact Probability: Low**

A new first-party package, `Webkul\Omnibus`, records per-channel lowest-price snapshots for EU Omnibus Directive compliance. It is **off by default** (`catalog.products.omnibus.is_enabled`, falling back to `OMNIBUS_ENABLED`), so an upgraded store behaves exactly as before until it is switched on.

Being a new package, it needs the three registrations any Bagisto package needs. If you have not customised these files, taking them from the release is enough:

```diff
  // composer.json
+ "Webkul\\Omnibus\\": "packages/Webkul/Omnibus/src",

  // bootstrap/providers.php
+ OmnibusServiceProvider::class,

  // config/concord.php
+ Webkul\Omnibus\Providers\ModuleServiceProvider::class,
```

Run `composer dump-autoload` if you edited `composer.json` by hand. `php artisan migrate` creates the `product_omnibus_prices` table.

Once enabled, the package leans on the scheduler — `omnibus:snapshot-prices` every fifteen minutes and `omnibus:purge-old-snapshots` daily — so `php artisan schedule:run` has to be running on cron. History accumulates from the first snapshot onward, so the storefront shows a 30-day low only once thirty days of snapshots exist.

---

### PostgreSQL Support

**Impact Probability: Medium**

Bagisto now runs on PostgreSQL 16 alongside MySQL 8.0 and MariaDB 10.11, and CI covers all three. Existing MySQL and MariaDB installations are unaffected and need no action.

> [!NOTE]
> Three dozen migrations changed in this release for PostgreSQL's sake — `json` columns declared as `jsonb`, `boolean` flags that hold more than two values declared as `tinyInteger`, an index check that reads its prefix from `DB`, a nullable `category_translations.url_path`, and a `CONCAT()` rewritten through `db_grammar()`. Every one of them edits a migration that already ran on your store, so `php artisan migrate` will not replay them and there is nothing to do. They shape fresh installs only.

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

The production Docker images ship in all three flavours — see [`docker/production/README.md`](docker/production/README.md).

---

### Search Architecture Refactored to Engine-Agnostic Design

**Impact Probability: High**

Bagisto v2.5 replaces the tightly-coupled Elasticsearch search infrastructure in the `Product` package with an engine-agnostic design using the Strategy and Manager patterns. This enables swapping search engines (e.g., Algolia, Pinecone) without modifying core code.

> [!IMPORTANT]
> If your store searches with Elasticsearch and its credentials live only in `.env`, read [Elasticsearch connection settings are now recorded in the admin](#elasticsearch-connection-settings-are-now-recorded-in-the-admin) below before you upgrade. This is the one part of the refactor that can take a working store offline.

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

The `Webkul\Product\Helpers\Product` class has been deleted. Its only method moved to `ElasticSearchEngine`, unchanged, so index names are the same and no reindex is required for the rename alone:

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

Three contracts define the engine abstraction:

- `Webkul\Product\Contracts\SearchEngine` — search operations (`search`, `getSuggestions`, `getMaxPrice`, `findBySlug`)
- `Webkul\Product\Contracts\SearchIndexer` — indexing operations (`indexBatch`, `deleteBatch`, `reindexFull`)
- `Webkul\Product\Contracts\SearchEngineConnection` — optional, for an engine reached over a network (`configure`, `probe`, `describesRecorded`). An engine that needs nothing to reach, such as the database, simply does not implement it.

#### New Services

| Class | Purpose |
|-------|---------|
| `Services\Search\SearchEngineManager` | Centralizes all config resolution — single source of truth for which engine/driver is active |
| `Services\Search\Engines\DatabaseEngine` | `SearchEngine` implementation for database mode |
| `Services\Search\Engines\ElasticSearchEngine` | `SearchEngine` implementation for Elasticsearch |
| `Services\Search\Indexers\ElasticSearchIndexer` | `SearchIndexer` implementation wrapping the existing `Helpers\Indexers\ElasticSearch` |
| `Services\Search\Indexers\NullIndexer` | No-op `SearchIndexer` for database mode (eliminates config guards) |
| `Services\Search\Connections\ElasticConnection` | `SearchEngineConnection` for Elasticsearch — applies the admin settings and probes the cluster |
| `Services\Search\SearchEngineAvailability` | Probes an engine and caches the verdict, keyed per engine |
| `Services\Search\SearchEngineConfigurator` | Applies every connectable engine's recorded settings at boot |
| `Services\Search\SearchEngineOptions` | The shared option list behind the three engine selects |
| `Enums\SearchEngineStatusEnum` | `available`, `unreachable`, `unauthorized`, `incompatible`, `misconfigured` |

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

   If you were calling `ElasticSearchRepository` methods directly, resolve the engine through the manager instead of naming the class, so the store's own configuration decides which one you get:

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

6. **Update the config check in a custom DataGrid:**

   A DataGrid that decides for itself whether to search with Elasticsearch should ask the manager rather than reading the config codes, which have moved:

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
   +
   + if ($manager->resolveDriver(SearchContextEnum::ADMIN) === SearchEngineEnum::DATABASE) {
         parent::processRequest();
         return;
     }
   ```

   The query itself is unchanged. `Webkul\Core\Facades\ElasticSearch` is still the supported way to issue a raw Elasticsearch query, and the core `ProductDataGrid` still uses it — only the decision of *whether* to reach for it moved behind the manager.

7. **Reading configuration through the manager:**

   ```php
   use Webkul\Product\Enums\SearchContextEnum;
   use Webkul\Product\Enums\SearchEngineEnum;
   use Webkul\Product\Services\Search\SearchEngineManager;

   $manager = app(SearchEngineManager::class);

   if ($manager->isExternalEngineEnabled()) {
       // An engine other than the database is switched on
   }

   if ($manager->resolveDriver(SearchContextEnum::STOREFRONT) === SearchEngineEnum::ELASTIC) {
       // This context searches with Elasticsearch
   }
   ```

   `engine(SearchContextEnum)`, `indexer()` and `getMasterEngine()` round out the API.

8. **Update stored configuration codes:**

   Search settings have moved out of Catalog into a **Search Engines** section of their own, at
   `Configuration → Search Engines`. The stored `core_config` codes move with them, and a migration
   carries existing values across:

   | Before | After |
   |---|---|
   | `catalog.products.search.engine` | `search_engines.general.settings.engine` |
   | `catalog.products.search.admin_mode` | `search_engines.general.products.admin_mode` |
   | `catalog.products.search.storefront_mode` | `search_engines.general.products.storefront_mode` |
   | `catalog.products.search.min_query_length` | `search_engines.elastic.settings.min_query_length` |
   | `catalog.products.search.max_query_length` | `search_engines.elastic.settings.max_query_length` |

   A new `search_engines.general.settings.enabled` switch now gates every external engine — when it
   is off, both contexts search the database whatever the modes say. The migration turns it on for
   any store that already had a non-database engine selected, so an upgrade keeps searching the way
   it did before.

   The two context modes also accept an empty value meaning *use the default engine*, so a store no
   longer has to repeat its engine choice in three places.

   Read them through `SearchEngineManager` rather than by code, as in step 7 — its `ENABLED_KEY`,
   `ENGINE_KEY`, `ADMIN_MODE_KEY` and `STOREFRONT_MODE_KEY` constants are the supported reference.

#### Elasticsearch connection settings are now recorded in the admin

Host, credentials, authentication type and index prefix can be set at `Configuration → Search Engines → Elasticsearch`, and `SearchEngineConfigurator` applies them over `config/elasticsearch.php` at boot.

> [!WARNING]
> This is not purely additive, and it is the one step an Elasticsearch store must act on.
>
> `ElasticConnection::configure()` returns early only while the store has recorded **no** Elasticsearch setting at all. The migration in step 8 records `search_engines.elastic.settings.min_query_length` and `max_query_length` for any store that had saved the old catalog search page — which is every store that turned Elasticsearch on from the admin. From then on, `configure()` runs in full: it sets `elasticsearch.connection` from the recorded authentication type, and **nulls out the credentials that type does not read**.
>
> With the credential fields still empty after the upgrade, the authentication type resolves to *none*, which means the `default` connection with `user` and `pass` cleared. A store whose `ELASTICSEARCH_CONNECTION` was `api` or `cloud`, or whose cluster is behind `ELASTICSEARCH_USER` / `ELASTICSEARCH_PASS`, will stop being able to reach it.
>
> **After migrating, open `Configuration → Search Engines → Elasticsearch`, set the authentication type, re-enter the host and credentials, and use Test Connection.** The `ELASTICSEARCH_*` variables remain the fallback for anything you leave blank, so a cluster reached anonymously over the default connection needs nothing.

Changing the index prefix here has the same effect as changing `ELASTICSEARCH_INDEX_PREFIX`: it renames the indices the store reads, so reindex with `php artisan indexer:index --type=search --mode=full` afterwards.

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

   The `SearchEngineManager` resolves your engine as soon as the stored config value matches the enum case.

5. If your engine is reached over a network, implement `Webkul\Product\Contracts\SearchEngineConnection`
   and bind it as well. This is what gives it settings in the admin and a working Test Connection
   button; an engine without one is treated as always available.

   ```php
   class AlgoliaConnection implements SearchEngineConnection
   {
       public function configure(array $overrides = []): void { /* Push stored settings into config() */ }
       public function probe(array $overrides = []): array { /* Return ['status' => SearchEngineStatusEnum::AVAILABLE->value, ...] */ }
       public function describesRecorded(array $overrides): bool { /* Do these values match what is stored? */ }
   }
   ```

   ```php
   $this->app->singleton('product.search.connection.algolia', AlgoliaConnection::class);
   ```

   `configure()` runs at boot for every bound connection, so keep it cheap and read `core_config`
   directly rather than through a repository — the table may not exist yet on a fresh install. Return
   early when nothing is recorded, so the environment file stays in charge of an unconfigured store.
   `probe()` must return a `status` drawn from `SearchEngineStatusEnum`; anything else it returns is
   passed through to the admin as detail. `describesRecorded()` lets the Test Connection button tell a
   trial of unsaved values from a test of the saved ones — both `configure()` and `probe()` take the
   same `$overrides` array for that purpose.

6. Add a configuration group keyed by the enum value, so the settings land on their own page:

   ```php
   ['key' => 'search_engines.algolia', 'name' => '...engines.algolia', 'icon' => '...', 'sort' => 3],
   ['key' => 'search_engines.algolia.settings', 'fields' => [/* App id, api key, ... */]],
   ```

   > [!IMPORTANT]
   > Keep the `settings` segment. `SystemConfig::getDefaultConfig()` strips the **first** segment of a
   > config key and looks the rest up in `config()`, so `search_engines.algolia.<field>` would resolve
   > against a `config/algolia.php` if one existed and silently override your declared default.

7. Add the engine's label at `admin::app.configuration.index.search-engines.engines.algolia` in all
   22 locales. It names the option in every engine select and titles the settings page.

The status strings under `search-engines.test-connection.statuses.*` take an `:engine` placeholder,
so they read correctly for your engine without being touched.

---

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
+ @import "tailwindcss" source("../../../");
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

In v3, `content: ["./src/Resources/**/*.blade.php"]` explicitly listed every glob. In v4, source detection is automatic but rooted in a **base source directory** you declare, given relative to the `app.css` that declares it:

- `@import "tailwindcss" source("../../../");` — the base Admin and Shop use, which resolves to the package's `src/`. Tailwind then auto-detects `.blade.php`, `.js`, `.vue` and other recognized file types under that path. Rooting at `src/` rather than `src/Resources/` is deliberate: class names written in `Config` and `DataGrids` files — menu, ACL and datagrid icons — are seen without help. The Installer, which has none, roots at `src/Resources/` with `source("../../")`.
- `@source "../../../../../SomePackage/src/Resources/**/*.blade.php";` — adds a specific glob outside the base directory. Count the hops from the directory holding `app.css`, not from the package root.
- `@source inline("icon-a icon-b icon-c");` — the v4 replacement for v3's `safelist` regex, since regex patterns are no longer supported. Enumerate literal class names, or use brace expansion as the storefront does: `@source inline("{icon-dollar-sign,icon-product,icon-share,icon-support,icon-truck}");`.

**Breaking behavior:** v3's `safelist` accepted regex patterns like `{ pattern: /icon-/ }`. v4's `@source inline(...)` does **not** support regex. If your custom package relied on a regex safelist, you must either enumerate every class explicitly, or move the class definitions out of `@layer components` so they are not tree-shaken.

#### The Default Font Stack Changed

v4 dropped `system-ui` from the front of its default `--font-sans`, which on Linux and Windows falls through to a different face entirely — Roboto or Arial rather than the desktop UI font. Admin and Shop pin the v3 stack back:

```css
@theme {
    --font-sans: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
}
```

If your theme overrides `app.css` from scratch and did not set its own font, add the same declaration or the whole storefront shifts typeface.

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
| Admin | `icon-cancel-1` | `icon-close` | plain ✕ (`icon-cancel` still carries the circled ✕) |
| Admin | `icon-customer-2` | `icon-customer` | person |
| Admin | `icon-checkbox-partical` | `icon-checkbox-partial` | spelling fix |
| Shop | `icon-filter-1` | `icon-funnel` | funnel (`icon-filter` still carries the sliders) |
| Shop | `icon-compare-1` | `icon-swap` | curved crossing arrows (`icon-compare` is unchanged) |
| Shop | `icon-sort-1` | `icon-sort` | descending lines |

> [!IMPORTANT]
> Two of those renames took a name that already existed. `icon-customer` and `icon-sort` still work, but now draw the glyph their `-2` / `-1` sibling used to draw, and the glyph they drew in v2.4 is no longer declared. A custom view using either name will render a different icon rather than none, which is easy to miss. Their old glyphs are still in the font files and can be declared under a name of your own:
>
> ```css
> @theme {
>     --icon-customer-outline: "\e912";  /* the v2.4 icon-customer */
>     --icon-sort-grey:        "\e937";  /* the v2.4 icon-sort */
> }
> ```

**Removed icons.** Glyphs that no core view referenced were dropped from the stylesheets so they no longer ship as dead CSS — 10 from Admin, 15 from Shop, 2 from Installer:

| Package | Removed |
|---------|---------|
| Admin | `icon-add-customer`, `icon-ar`, `icon-clip`, `icon-edit-save`, `icon-order-back`, `icon-product-1`, `icon-refund`, `icon-setting`, `icon-tick`, `icon-zoom` |
| Shop | `icon-Free-Shipping`, `icon-add-new`, `icon-astreisk`, `icon-box-fill`, `icon-camera-fill`, `icon-dislike`, `icon-email`, `icon-filter-fill`, `icon-heart-1`, `icon-heart-2`, `icon-left-arrow`, `icon-like`, `icon-right-arrow`, `icon-sort-by`, `icon-tick` |
| Installer | `icon-arrow-down`, `icon-view` |

The glyphs are still in the font files; if your package uses one, declare it in your own CSS:

```css
@theme {
    --icon-refund: "\e948";
}
```

**Safelisting.** With the scan root at `src/`, only names that no file in the package mentions still need `@source inline(...)`; in the storefront that is the five icons named by the SocialShare view and by the theme content the installer seeds.

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

4. **Rewrite `app.css`** — replace `@tailwind` directives with `@import "tailwindcss" source("...");`, pointing at the directory your class names are written under, and translate any `theme.extend.*` values from your old JS config into `@theme` blocks. If you overrode Bagisto's colors, breakpoints, or fonts, register them in `@theme` with `--color-*`, `--breakpoint-*`, `--font-*` variables.

5. **Rebuild frontend assets** as shown in [The Upgrade Itself](#the-upgrade-itself).

6. **Visually verify** the storefront, admin panel, and installer flow. Common regression spots after a v4 upgrade:

   - Missing icons — if custom icon classes drop out, safelist them with `@source inline("icon-name-a icon-name-b ...");` in your `app.css`.
   - The wrong icon in the right place — check any use of `icon-customer` or `icon-sort` against the renames above.
   - Elements with bare `border` render the wrong color — confirm the base-layer compat rule (see the "v3 Compatibility Base Layer" section above) is present.
   - Custom buttons without a `cursor-pointer` class show the arrow cursor — confirm the same base-layer rule.
   - The whole page in a different typeface — see "The Default Font Stack Changed" above.

   For any additional utility-class-level breaking changes in your custom Blade templates, refer to the official [Tailwind CSS v4 upgrade guide](https://tailwindcss.com/docs/upgrade-guide).
