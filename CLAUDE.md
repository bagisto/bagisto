# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Bagisto 2.5.x - open-source Laravel 13 e-commerce platform. PHP 8.4+, Vue.js 3, Tailwind CSS 4, Vite 6.

Runs on MySQL 8.0, MariaDB 10.11 or PostgreSQL 16; all three are first-class and CI covers each.

## Skills — load the relevant one before writing code, not after

This repository ships its conventions as skills under `.claude/skills/`, invoked with the Skill
tool. They carry rules that `vendor/bin/pint` does not enforce and that a reviewer will otherwise
send back.

**`bagisto-coding-standards` applies to nearly every change** — it owns code style, comments and
docblocks, Laravel idiom, Blade, database access, security and localization. Load it alongside
whichever of the below fits the task.

| Working on | Load |
|---|---|
| Any PHP or Blade | `bagisto-coding-standards` |
| A package: providers, models, repositories, routes, controllers, ACL, menus, config | `bagisto-package-development` |
| An admin listing page | `bagisto-datagrid-development` |
| Attributes, families, EAV values | `bagisto-attribute-development` |
| The Appearance area — theme sections, the editor, its preview | `bagisto-theme-sections` |
| Imports — Importer classes, the queued pipeline | `bagisto-data-transfer` |
| A payment gateway | `bagisto-payment-method-development` |
| A shipping carrier | `bagisto-shipping-method-development` |
| A product type | `bagisto-product-type-development` |
| A storefront or admin theme | `bagisto-shop-theme-development` / `bagisto-admin-theme-development` |
| A storefront feature on the advanced theme workflow | `bagisto-shop-advance-theme-development` |
| Pest tests | `bagisto-pest-testing` |
| Playwright end-to-end tests | `bagisto-playwright-testing` |
| Reviewing a change | `bagisto-code-review` |
| Branching, commits, CHANGELOG, PRs | `bagisto-git-workflow` |
| The REST/GraphQL API | `bagisto-api-develop` / `bagisto-api-shop` / `bagisto-api-admin` |
| Any documentation site — developer docs, user guide, screenshots | `bagisto-documentation` |

**Load `bagisto-change-verification` before calling any change done** — it owns the gates (Pint,
Pest, Playwright, translation completeness).

One rule that catches people out:

- **A pre-existing violation in a file you touch is yours.** `bagisto-coding-standards` is
  explicit: when you edit a class, scan its whole member order and docblocks and fix what is
  already wrong. Leaving it is treated the same as introducing it.

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
vendor/bin/pest --parallel                              # Run all tests in parallel (6 processes)
vendor/bin/pest --testsuite="Admin Feature Test"        # Run a specific test suite
vendor/bin/pest packages/Webkul/Admin/tests/Feature     # Run tests in a directory
vendor/bin/pest --filter="test name"                    # Run a single test by name
```

Test suites defined in `phpunit.xml`: Unit (cross-package, needs no database), Admin Feature, Core Unit, Customer Unit, DataGrid Unit, EUWithdrawal Feature, FPC Unit/Feature, Installer Feature, Omnibus Feature, PayGlocal Unit/Feature, PayU Unit/Feature, Razorpay Unit/Feature, Shop Feature, Stripe Unit/Feature.

Every package that has tests is registered above. Packages without a `tests/` directory (PhonePe, Checkout, Product, Sales, RMA, and others) have no suite — adding a `<testsuite>` for a path that does not exist makes PHPUnit error, so write the tests first.

Tests use **Pest 5** (PHPUnit 13) with package-specific TestCase classes bound in `tests/Pest.php`. Each package's tests live in `packages/Webkul/<Package>/tests/`.

### Fresh Database Setup for Testing
Parallel testing creates databases named `{DB_DATABASE}_test_1`, `{DB_DATABASE}_test_2`, etc. based on the number of CPU cores. For example, with `DB_DATABASE=bagisto` on a 6-core machine, it creates `bagisto_test_1` through `bagisto_test_6`. This applies to MySQL, MariaDB and PostgreSQL alike.

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
npm run install:browsers
npm run test:e2e
```

**Shop**:
```bash
cd packages/Webkul/Shop
npm install
npm run install:browsers
npm run test:e2e
```

**Installer** — drives the guided web installer, so it runs against an *uninstalled* application
(no `bagisto:install`, only `php artisan key:generate`). Specs are tagged per locale:
```bash
cd packages/Webkul/Installer
npm install
npm run install:browsers
npm run test:e2e -- --grep "@en"
```
The database it installs into comes from the `INSTALLER_DB_*` env vars, defaulting to MySQL on
127.0.0.1:3306.

Admin and Shop tests require a running Laravel server (`php artisan serve`) and a seeded database.
The base URL comes from `APP_URL` in the application `.env`, falling back to `BASE_URL`; every
environment value is read and validated once in `tests/e2e-pw/utils/env.ts`, never from
`process.env` elsewhere. A suite also honours its own `tests/e2e-pw/.env` when one exists, and
locates the application by searching upward for `artisan`, so it keeps working if the folder moves.

Each package exposes the same scripts, all run from the package directory:

| Script | Purpose |
|---|---|
| `npm run test:e2e` | Run the suite; append `-- --grep …`, `-- --shard=i/n`, or a spec path |
| `npm run test:e2e:headed` / `:ui` / `:debug` | Visible browser, UI mode, inspector |
| `npm run test:e2e:report` | Open the last HTML report |
| `npm run install:browsers` | Install the pinned Chromium with its system deps |
| `npm run typecheck` | `tsc --noEmit` over the suite |
| `npm run format` / `format:check` | Prettier write / CI-style check, scoped to `tests/e2e-pw` |

`typecheck` and `format:check` are not yet clean on the existing specs and page objects, so they
are local tools rather than CI gates.

### Code Style
```bash
vendor/bin/pint             # Fix PHP code style (Laravel Pint)
vendor/bin/pint --test      # Check style without fixing
```

**Important:** Always run `vendor/bin/pint` on modified files after every code change before running tests or marking work as complete.

#### Multi-condition control flow

When an `if` / `elseif` / `while` / `for` condition contains more than one expression joined by `&&` or `||`, split it across multiple lines with each expression on its own line and the boolean operator leading the next line:

```php
// Good
if (
    $user->isActive()
    && $user->hasRole('admin')
) {
    return true;
}

// Avoid
if ($user->isActive() && $user->hasRole('admin')) {
    return true;
}
```

Single-condition statements stay on one line. Pint/PHP-CS-Fixer has no rule that enforces this automatically — it is a manual convention, so apply it when writing or reviewing code.

**Do not write comments inside method bodies.** Keep only the docblock above a class, method, or property — that is the only comment this codebase wants. Do not narrate what a statement does, why a line was added, or what a fix changed; the code and the commit message carry that. This applies to `//` line comments and `/** */` blocks alike, and to PHP, Blade, JavaScript, and Vue.

```php
// Bad - explains a statement inside the body
public function updateStatus(int $id): RedirectResponse
{
    // Re-fetch the cart because collectTotals swapped the instance
    $cart = Cart::getCart();
}

// Good - docblock only, body speaks for itself
/**
 * Update RMA status.
 */
public function updateStatus(int $id): RedirectResponse
{
    $cart = Cart::getCart();
}
```

If a line genuinely cannot be understood without prose, that is a signal to extract a well-named method instead of annotating it.

### Translations
When adding new translation keys, always provide translations for **all locales** in the package's `Resources/lang/` directory. Verify with:
```bash
php artisan bagisto:translations:check
```

## Architecture

### Modular Package System

All core functionality lives in **`packages/Webkul/`** (42 packages). Each package is a self-contained Laravel package with its own models, controllers, routes, views, migrations, and service providers.

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

- **pest-tests.yml**: Pest tests on PHP 8.4 × MySQL 8.0, MariaDB 10.11 and PostgreSQL 16
- **pint-tests.yml**: Code style checks with Laravel Pint
- **playwright-tests.yml**: E2E tests. An `installer_gate` job runs the guided installer (English
  and Arabic × each database) and gates `playwright_tests`, which runs the Admin and Shop projects
  across 10 shards × each database
- **translation-tests.yml**: Translation file validation
- **docker-publish.yml**: Matrix-builds and pushes every server × database image on a `v*` tag

## Production Docker Images

Images are built from `docker/production/` across two dimensions — web server (`nginx`, `apache`, `litespeed`) and bundled database (`mysql`, `mariadb`, `postgres`) — and published as `webkul/bagisto:<version>-<server>-<database>`, nine combinations in all. MySQL images also answer to the shorter `-<server>` name, and nginx + MySQL to the bare `:<version>` and `:latest`.

Everything engine-specific lives in `docker/production/shared/db/<engine>/` behind a fixed contract (`engine.sh` exposing `db_default_port`, `db_connection`, `db_server_packages`, and the build init/start/wait/provision/stop and runtime ping functions). The Dockerfiles, `build-install.sh` and `entrypoint.sh` never name a database — they source the driver — so adding an engine means adding a directory, not editing the shared scripts.

```bash
cd docker/production

docker build -f nginx/Dockerfile -t bagisto:nginx-mysql .
docker build -f nginx/Dockerfile -t bagisto:nginx-mariadb --build-arg DB_ENGINE=mariadb .
docker build -f nginx/Dockerfile -t bagisto:nginx-postgres --build-arg DB_ENGINE=postgres .
```

## PostgreSQL Compatibility

The codebase must work on both MySQL and PostgreSQL. Use the existing abstractions:

- **Case-insensitive LIKE**: Use `db_grammar()->caseInsensitiveLike()` instead of hardcoded `'like'`. Returns `LIKE` on MySQL (already case-insensitive), `ILIKE` on PostgreSQL. Use `db_grammar()->caseSensitiveLike()` when exact case matching is needed.
- **Empty strings → NULL/default**: Use model set mutators (`setXxxAttribute`), never sanitize in controllers.
- **Boolean columns**: Add `$casts` with `'boolean'`. For write-side, use repository validation or model mutators.
- **DB-specific SQL**: Use `db_grammar()` methods (`concat`, `groupConcat`, `findInSet`, `dateFormat`, `jsonExtract`, `caseInsensitiveLike`, `caseSensitiveLike`, etc.).
- **CASE expression types**: Both branches must return same type. Use `CAST(id AS VARCHAR(255))` — `CAST(... AS CHAR)` truncates to 1 character on PostgreSQL.
- **GROUP BY**: PostgreSQL requires every non-aggregated SELECT column in GROUP BY.
- **DB::raw() in updateOrCreate**: Fails on INSERT; split into find + update/create.
