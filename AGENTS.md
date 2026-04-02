# AGENTS.md — Cross-Agent Instructions for Bagisto 2.4.x

## Do Not Edit

- `vendor/`, `node_modules/`, `composer.lock`, `package-lock.json`
- `public/themes/*/build/` — Vite build output
- `storage/` — runtime caches, logs, compiled views
- `*.hot` files — Vite HMR markers
- `packages/Webkul/*/src/Resources/assets/` — only edit if working on frontend; always run `npm run build` from the respective package directory after

## Repository Map

```
├── app/                        # Thin Laravel app shell (middleware, providers)
├── bootstrap/
│   ├── app.php                 # Middleware, exceptions, routing
│   └── providers.php           # All service provider registrations
├── config/
│   ├── concord.php             # Concord module (model proxy) registrations
│   ├── themes.php              # Shop + Admin theme config (Vite paths)
│   ├── elasticsearch.php       # Elasticsearch connection
│   └── ...                     # Standard Laravel configs
├── database/
│   ├── migrations/             # App-level migrations
│   └── seeders/
├── packages/Webkul/            # ★ All Bagisto packages live here (40 packages)
│   ├── Admin/                  # Admin panel (controllers, views, DataGrids, reporting, e2e-pw tests)
│   ├── Shop/                   # Customer storefront (controllers, views, e2e-pw tests)
│   ├── Core/                   # Helpers, models, jobs, listeners, exchange rates
│   ├── Product/                # Product models, types, indexers, repositories
│   ├── Sales/                  # Orders, invoices, shipments, refunds
│   ├── Checkout/               # Cart, checkout flow
│   ├── Customer/               # Customer models, auth
│   ├── Category/               # Category tree (nested set)
│   ├── Attribute/              # EAV attribute system
│   ├── Payment/                # Base payment classes (CashOnDelivery, MoneyTransfer)
│   ├── Paypal/                 # PayPal integration
│   ├── Stripe/                 # Stripe integration
│   ├── Razorpay/               # Razorpay integration
│   ├── PayU/                   # PayU integration
│   ├── Shipping/               # Base shipping carriers
│   ├── Inventory/              # Stock management
│   ├── CartRule/               # Cart promotion rules
│   ├── CatalogRule/            # Catalog price rules
│   ├── Tax/                    # Tax calculation
│   ├── DataGrid/               # Admin data table component
│   ├── DataTransfer/           # Import/export
│   ├── CMS/                    # CMS pages
│   ├── Marketing/              # SEO, URL rewrites, search terms, campaigns
│   ├── Theme/                  # Theme management
│   ├── MagicAI/                # AI features (Laravel AI SDK)
│   ├── Notification/           # Notifications
│   ├── BookingProduct/         # Booking product type
│   ├── Rule/                   # Shared rule engine base
│   ├── User/                   # Admin user management
│   ├── Installer/              # Installation wizard
│   ├── SocialLogin/            # OAuth social login
│   ├── SocialShare/            # Social sharing
│   ├── Sitemap/                # XML sitemap generation
│   ├── GDPR/                   # GDPR compliance
│   ├── RMA/                    # Return merchandise authorization
│   ├── FPC/                    # Full page cache
│   ├── ImageCache/             # Image caching/resizing
│   ├── DebugBar/               # Debug toolbar
│   ├── BreezeFront/            # Breeze frontend theme
│   └── NewTheme/               # New theme scaffold
├── routes/
│   ├── web.php                 # Minimal — packages define their own routes
│   └── console.php
├── tests/
│   └── Pest.php                # Pest configuration binding test cases to packages
├── phpunit.xml                 # Test suites per package
├── pint.json                   # Pint config (preset: laravel)
├── vite.config.js              # Root Vite config
└── docker-compose.yml          # Sail: MySQL 8, Redis, Elasticsearch 7.17, Kibana, Mailpit
```

## Package Internal Structure

Every package in `packages/Webkul/{Name}/src/` follows:

```
├── Config/                     # admin-menu.php, system.php, acl.php, carriers.php, etc.
├── Contracts/                  # Interfaces for each model
├── Database/
│   ├── Migrations/
│   ├── Factories/
│   └── Seeders/
├── DataGrids/                  # DataGrid classes (extends Webkul\DataGrid\DataGrid)
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/               # Form Request validation classes
├── Jobs/
├── Listeners/
├── Models/                     # Eloquent models + Proxy classes
├── Observers/
├── Providers/
│   ├── {Name}ServiceProvider.php
│   └── ModuleServiceProvider.php  # Concord model registration
├── Repositories/               # Prettus L5 repositories
├── Resources/
│   ├── assets/                 # JS, CSS, images (Vite-compiled)
│   ├── lang/{locale}/          # 21 locales
│   └── views/
├── Routes/
│   ├── admin-routes.php
│   └── shop-routes.php
└── Type/                       # (Product package) Product type classes
```

## Key Architecture Patterns

- **Concord Module System**: Models registered in each package's `ModuleServiceProvider`, wired via `config/concord.php`. Every data entity has a Contract (interface), Model, and Proxy (three-component system).
- **Repository Pattern**: All DB access through repositories extending `Webkul\Core\Eloquent\Repository` (Prettus L5). Repository `model()` returns the Contract class, not the Model.
- **Path Repositories**: `composer.json` uses `"type": "path"` for `packages/*/*`, packages are symlinked — no `composer update` needed for package code changes. Run `composer dump-autoload` after adding new packages.
- **Service Providers**: Each package has a main ServiceProvider (routes, views, translations, migrations, config) registered in `bootstrap/providers.php`.
- **Dual Route Files**: Admin routes (`['web', 'admin']` middleware, `config('app.admin_url')` prefix) and Shop routes (`['web', 'locale', 'theme', 'currency']` middleware).
- **21 Locales**: ar, bn, ca, de, en, es, fa, fr, he, hi_IN, id, it, ja, nl, pl, pt_BR, ru, sin, tr, uk, zh_CN. Translation changes must be applied to ALL locale files. Verify with `php artisan bagisto:translations:check`.

## Commands

### Testing
```bash
# Pest (PHP)
vendor/bin/pest                                          # Run all tests
vendor/bin/pest --parallel                               # Run all tests in parallel
vendor/bin/pest --filter=testName                        # Run specific test
vendor/bin/pest packages/Webkul/Admin/tests/Feature      # Run package tests

# Playwright (E2E) — Admin (run from packages/Webkul/Admin)
cd packages/Webkul/Admin && npm install && npx playwright install --with-deps chromium
cd packages/Webkul/Admin && npx playwright test --config=tests/e2e-pw/playwright.config.ts

# Playwright (E2E) — Shop (run from packages/Webkul/Shop)
cd packages/Webkul/Shop && npm install && npx playwright install --with-deps chromium
cd packages/Webkul/Shop && npx playwright test --config=tests/e2e-pw/playwright.config.ts
```

### Fresh Database Setup for Parallel Testing
Parallel testing creates `{DB_DATABASE}_test_1`, `{DB_DATABASE}_test_2`, etc. based on the number of CPU cores. For example, with `DB_DATABASE=bagisto` on a 6-core machine, it creates `bagisto_test_1` through `bagisto_test_6`. This applies to both MySQL and PostgreSQL.

When the schema changes, these test databases become stale and must be dropped before re-running:

```bash
# Drop parallel test databases (adjust the count to match your CPU cores)
php artisan tinker --execute="for (\$i = 1; \$i <= 6; \$i++) { try { DB::statement(\"DROP DATABASE IF EXISTS bagisto_test_{\$i}\"); } catch (\Exception \$e) {} }"

# Fresh install
php artisan bagisto:install --skip-env-check --skip-admin-creation --skip-github-star

# Run tests
vendor/bin/pest --parallel --no-coverage
```

### Code Style
```bash
vendor/bin/pint --dirty          # Fix changed files only
vendor/bin/pint                  # Fix all files
vendor/bin/pint --test           # Check only (CI uses this)
```

**Important:** Always run `vendor/bin/pint` on modified files after every code change before running tests or marking work as complete.

### Commenting Conventions

- **Section headers / titles**: Title Case, no trailing period.
  ```php
  // Store
  // Product Attribute Values
  // Store — All Product Types
  ```
- **Inline labels** (grouping assertions inside a test): Title Case, no trailing period.
  ```php
  // Core fields
  // Text fields indexed from attribute values
  // Numeric fields
  // Boolean fields
  // Locale and channel
  ```
- **Sentence comments** (explanations, steps, notes): Start with a capital letter and end with a period.
  ```php
  // Step 1: Store the product skeleton via the controller.
  // Virtual products do not require weight, length, width, or height.
  // Verify product_flat reflects the changed values.
  ```
- **PHPDoc**: Every method should have a single-line description ending with a period.

### Frontend (run from within each package: Admin, Shop, or Installer)
```bash
cd packages/Webkul/Admin && npm install && npm run build    # Admin production build
cd packages/Webkul/Shop && npm install && npm run build     # Shop production build
cd packages/Webkul/Admin && npm run dev                     # Admin dev server with HMR
cd packages/Webkul/Shop && npm run dev                      # Shop dev server with HMR
```

### Database
```bash
php artisan migrate              # Run migrations
php artisan db:seed              # Seed database
```

## CI Workflows (.github/workflows/)

| Workflow | Trigger | What it does |
|----------|---------|--------------|
| `pest_tests.yml` | push, PR | Installs Bagisto, runs `vendor/bin/pest` |
| `pint_tests.yml` | push, PR | Runs `pint --test` (style check) |
| `admin_playwright_tests.yml` | push, PR | Admin E2E tests |
| `shop_playwright_tests.yml` | push, PR | Shop E2E tests |
| `translation_tests.yml` | push, PR | Translation key consistency |

## Safety Rails

- **Never modify `bootstrap/providers.php` or `config/concord.php`** without understanding the full provider chain — removing a provider breaks the entire module.
- **Translations are 21 files per key.** Missing a locale will fail CI. When adding/removing translation keys, hit all 21 files.
- **Pint must pass.** Run `vendor/bin/pint --dirty` before finalizing any PHP change.
- **Tests must pass.** Run affected package tests after changes. Do not delete tests without approval.
- **Do not add/remove composer dependencies without approval.**
- **Do not create documentation files unless explicitly requested.**

## Validation Checklist (Before Marking Complete)

1. `vendor/bin/pint --dirty` — no style violations
2. `php artisan test --compact` — affected tests pass
3. `php artisan bagisto:translations:check` — translation keys exist in all 21 locale files (if changed)
4. No `env()` calls outside `config/` files
5. New models have Contract + Model + Proxy + Repository
6. New packages registered in `bootstrap/providers.php` and `config/concord.php`
