# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Bagisto 2.4.x - open-source Laravel 12 e-commerce platform. PHP 8.3+, Vue.js 3, Tailwind CSS 3, Vite 5.

## Common Commands

### Development
```bash
composer install                # Install PHP dependencies
php artisan bagisto:install     # Full installation (migrations, seeders, assets)
php artisan serve               # Start PHP dev server
php artisan optimize:clear      # Clear all caches (run after config/code changes)
```

### Testing
```bash
vendor/bin/pest                                         # Run all tests
vendor/bin/pest --parallel                              # Run all tests in parallel (6 processes)
vendor/bin/pest --testsuite="Admin Feature Test"        # Run a specific test suite
vendor/bin/pest packages/Webkul/Admin/tests/Feature     # Run tests in a directory
vendor/bin/pest --filter="test name"                    # Run a single test by name
```

Test suites defined in `phpunit.xml`: Admin Feature, Core Unit, Customer Unit, DataGrid Unit, Installer Feature, PayU Unit/Feature, Razorpay Unit/Feature, Shop Feature, Stripe Unit/Feature.

Tests use **Pest 3** with package-specific TestCase classes bound in `tests/Pest.php`. Each package's tests live in `packages/Webkul/<Package>/tests/`.

### Fresh Database Setup for Testing
Parallel testing creates databases named `{DB_DATABASE}_test_1`, `{DB_DATABASE}_test_2`, etc. based on the number of CPU cores. For example, with `DB_DATABASE=bagisto` on a 6-core machine, it creates `bagisto_test_1` through `bagisto_test_6`. This applies to both MySQL and PostgreSQL.

When the schema changes, these test databases become stale and must be dropped before re-running:

```bash
# Drop parallel test databases (adjust the count to match your CPU cores)
php artisan tinker --execute="for (\$i = 1; \$i <= 6; \$i++) { try { DB::statement(\"DROP DATABASE IF EXISTS bagisto_test_{\$i}\"); } catch (\Exception \$e) {} }"

# Fresh install
php artisan bagisto:install --skip-env-check --skip-admin-creation --skip-github-star

# Run tests
vendor/bin/pest --parallel --no-coverage
```

### E2E Tests (Playwright)
E2E tests are run from within each package directory. Each package has its own Playwright config and tests:

**Admin**:
```bash
cd packages/Webkul/Admin
npm install
npx playwright install --with-deps chromium
npx playwright test --config=tests/e2e-pw/playwright.config.ts
```

**Shop**:
```bash
cd packages/Webkul/Shop
npm install
npx playwright install --with-deps chromium
npx playwright test --config=tests/e2e-pw/playwright.config.ts
```

Tests require a running Laravel server (`php artisan serve`) and seeded database. Set `BASE_URL` env var if not using default.

### Code Style
```bash
vendor/bin/pint             # Fix PHP code style (Laravel Pint)
vendor/bin/pint --test      # Check style without fixing
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

### Translations
When adding new translation keys, always provide translations for **all locales** in the package's `Resources/lang/` directory. Verify with:
```bash
php artisan bagisto:translations:check
```

## Architecture

### Modular Package System

All core functionality lives in **`packages/Webkul/`** (~42 packages). Each package is a self-contained Laravel package with its own models, controllers, routes, views, migrations, and service providers.

**Dual registration**: Each package registers in two places:
1. **`bootstrap/providers.php`** - Main ServiceProvider (routes, views, events, config)
2. **`config/concord.php`** - ModuleServiceProvider (Konekt Concord model/enum registration)

### Key Design Patterns

**Repository Pattern**: All database access goes through repositories (`Prettus L5 Repository`). Interfaces in `Contracts/`, implementations in `Repositories/`. Never use models directly for queries in controllers.

**Proxy Pattern**: Models have Proxy classes (e.g., `ProductProxy`, `CategoryProxy`) enabling model substitution without modifying core code. Always reference proxies when type-hinting across packages.

**Event-Driven Extensibility**: The framework fires events at key lifecycle points. Extend behavior via listeners rather than modifying core packages.

### Package Anatomy

```
packages/Webkul/<Package>/src/
├── Config/           # system.php (admin settings), admin-menu.php, acl.php
├── Database/         # Migrations/, Seeders/, Factories/
├── Http/Controllers/ # Separate Admin/ and Shop/ controller directories
├── Models/           # Eloquent models + Proxy classes
├── Repositories/     # Data access layer
├── Contracts/        # Interfaces for models and repositories
├── Resources/
│   ├── views/        # Blade templates (admin/, shop/)
│   ├── lang/         # Localization (translatable strings)
│   └── assets/       # CSS/JS source files
├── Routes/           # admin-routes.php, shop-routes.php, api.php
├── Providers/        # ServiceProvider + ModuleServiceProvider
└── Listeners/        # Event listeners
```

### Frontend Assets

Admin, Shop, and Installer each have independent Vite builds. Run `npm install` and `npm run dev`/`npm run build` from within the respective package directory:
- **Admin**: `packages/Webkul/Admin/` builds to `public/themes/admin/default/build/`
- **Shop**: `packages/Webkul/Shop/` builds to `public/themes/shop/default/build/`
- **Installer**: `packages/Webkul/Installer/`

Vue 3 components are used within Blade templates via `@pushOnce('scripts')` / Blade component slots.

### Naming Conventions

- **Namespace**: `Webkul\<PackageName>` (e.g., `Webkul\Product`)
- **Routes**: Separate `admin-routes.php` and `shop-routes.php` per package
- **Models**: Singular (`Product`, `Category`)
- **Repositories**: `<Model>Repository` (e.g., `ProductRepository`)
- **Controllers**: `<Model>Controller` in `Http/Controllers/Admin/` or `Shop/`

### Adding a New Package

1. Create `packages/Webkul/<Name>/src/` with the standard structure
2. Add PSR-4 namespace to root `composer.json` autoload
3. Register ServiceProvider in `bootstrap/providers.php`
4. Register ModuleServiceProvider in `config/concord.php`
5. Run `composer dump-autoload && php artisan optimize:clear`

Or use: `php artisan package:make Webkul/<Name>` (requires `bagisto/bagisto-package-generator`)

## CI Pipeline

- **pest_tests.yml**: Pest tests on PHP 8.3 + MySQL 8.0 & PostgreSQL 16
- **pint_tests.yml**: Code style checks with Laravel Pint
- **admin_playwright_tests.yml / shop_playwright_tests.yml**: E2E tests (6 shards × 2 databases)
- **translation_tests.yml**: Translation file validation

## PostgreSQL Compatibility

The codebase must work on both MySQL and PostgreSQL. Use the existing abstractions:

- **Case-insensitive LIKE**: Use `db_grammar()->caseInsensitiveLike()` instead of hardcoded `'like'`. Returns `LIKE` on MySQL (already case-insensitive), `ILIKE` on PostgreSQL. Use `db_grammar()->caseSensitiveLike()` when exact case matching is needed.
- **Empty strings → NULL/default**: Use model set mutators (`setXxxAttribute`), never sanitize in controllers.
- **Boolean columns**: Add `$casts` with `'boolean'`. For write-side, use repository validation or model mutators.
- **DB-specific SQL**: Use `db_grammar()` methods (`concat`, `groupConcat`, `findInSet`, `dateFormat`, `jsonExtract`, `caseInsensitiveLike`, `caseSensitiveLike`, etc.).
- **CASE expression types**: Both branches must return same type. Use `CAST(id AS CHAR)`.
- **GROUP BY**: PostgreSQL requires every non-aggregated SELECT column in GROUP BY.
- **DB::raw() in updateOrCreate**: Fails on INSERT; split into find + update/create.
