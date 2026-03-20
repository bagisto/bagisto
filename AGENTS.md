# AGENTS.md — Cross-Agent Instructions for Bagisto 2.4.x

## Do Not Edit

- `vendor/`, `node_modules/`, `composer.lock`, `package-lock.json`
- `public/themes/*/build/` — Vite build output
- `storage/` — runtime caches, logs, compiled views
- `*.hot` files — Vite HMR markers
- `packages/Webkul/*/src/Resources/assets/` — only edit if working on frontend; always run `npm run build` after

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
- **21 Locales**: ar, bn, ca, de, en, es, fa, fr, he, hi_IN, id, it, ja, nl, pl, pt_BR, ru, sin, tr, uk, zh_CN. Translation changes must be applied to ALL locale files.

## Commands

### Testing
```bash
# Pest (PHP)
php artisan test --compact                              # Run all tests
php artisan test --compact --filter=testName             # Run specific test
php artisan test --compact packages/Webkul/Admin/tests   # Run package tests

# Playwright (E2E) — Admin
cd packages/Webkul/Admin/tests/e2e-pw && npx playwright test

# Playwright (E2E) — Shop
cd packages/Webkul/Shop/tests/e2e-pw && npx playwright test
```

### Code Style
```bash
vendor/bin/pint --dirty          # Fix changed files only
vendor/bin/pint                  # Fix all files
vendor/bin/pint --test           # Check only (CI uses this)
```

### Frontend
```bash
npm run build                    # Production build (Vite)
npm run dev                      # Dev server with HMR
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
3. Translation keys exist in all 21 locale files (if changed)
4. No `env()` calls outside `config/` files
5. New models have Contract + Model + Proxy + Repository
6. New packages registered in `bootstrap/providers.php` and `config/concord.php`
