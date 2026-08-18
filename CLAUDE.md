# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Bagisto 2.4.x - open-source Laravel 12 e-commerce platform. PHP 8.3+, Vue.js 3, Tailwind CSS 3, Vite 5.

## Skills — load these before writing code

This repository ships its conventions as skills under `.claude/skills/`. Invoke them with the Skill
tool — `package-development`, `blade-conventions`, `pest-testing`.

**Load the relevant ones before writing or reviewing code, not after.** They carry rules that
`vendor/bin/pint` does not enforce and that a reviewer will otherwise send back.

| Skill | Load it when | It settles |
|---|---|---|
| `package-development` | Any PHP: controllers, repositories, models, DataGrids, migrations, listeners, jobs, enums | Docblocks on every method/property, class member order, multi-clause conditions, comment discipline, repository-over-query-builder, package/menu/ACL/system-config structure |
| `blade-conventions` | Any `.blade.php`, in any package | `:` vs `::` binding, anonymous vs Vue-backed components, attribute layout, `@props` alignment, comment syntax per layer, translations and `view_render_event` placement |
| `pest-testing` | Writing or changing tests | Suite layout, test-case bindings, assertion style, datasets, registering a new package's tests |

One rule that catches people out:

- **A pre-existing violation in a file you touch is yours.** `package-development` is explicit:
  when you edit a class, scan its whole member order and docblocks and fix what is already wrong.
  Leaving it is treated the same as introducing it.

Where a skill and the surrounding code genuinely disagree, match the surrounding code and say so in
your summary rather than silently churning the codebase either way.

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
vendor/bin/pest --testsuite="Admin Feature Test"        # Run a specific test suite
vendor/bin/pest packages/Webkul/Admin/tests/Feature     # Run tests in a directory
vendor/bin/pest --filter="test name"                    # Run a single test by name
```

Test suites defined in `phpunit.xml`: Admin Feature, Core Unit, Customer Unit, DataGrid Unit, Installer Feature, PayGlocal Unit/Feature, PayU Unit/Feature, Razorpay Unit/Feature, Shop Feature, Stripe Unit/Feature.

Tests use **Pest 3** with package-specific TestCase classes bound in `tests/Pest.php`. Each package's tests live in `packages/Webkul/<Package>/tests/`.

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

- **pest_tests.yml**: Pest tests on PHP 8.3 + MySQL 8.0
- **pint_tests.yml**: Code style checks with Laravel Pint
- **admin_playwright_tests.yml / shop_playwright_tests.yml**: E2E tests (6 parallel shards)
- **translation_tests.yml**: Translation file validation
