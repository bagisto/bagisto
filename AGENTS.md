# AGENTS.md — Instructions for Bagisto 2.5.x

Bagisto 2.5.x is an open-source Laravel 13 e-commerce platform: PHP 8.4+, Vue.js 3, Tailwind CSS 4,
Vite 6. It runs on MySQL 8.0, MariaDB 10.11 or PostgreSQL 16; all three are first-class and CI
covers each.

This file is the single source of instructions for every coding agent. `CLAUDE.md` imports it, and
`.github/copilot-instructions.md` points here.

## Skills — load the relevant one before writing code, not after

The conventions are kept as skills under `.claude/skills/<name>/SKILL.md`. The directory is
git-ignored, so it is present only where the skills are installed. If your harness has no skill
loader, read those files directly — they are plain markdown. They carry rules that
`vendor/bin/pint` does not enforce and that a reviewer will otherwise send back.

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

## Do Not Edit

- `vendor/`, `node_modules/`, `composer.lock`, `package-lock.json`
- `public/themes/*/*/build/` — Vite output; regenerate it with `npm run build`, never hand-edit it
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
│   ├── themes.php              # Shop + Admin themes, incl. each shop theme's `customize` (sections, image cache)
│   ├── elasticsearch.php       # Elasticsearch connection defaults (admin settings override)
│   └── ...                     # Standard Laravel configs
├── database/
│   ├── migrations/             # App-level migrations
│   └── seeders/
├── packages/Webkul/            # ★ All Bagisto packages live here (42 packages)
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
│   ├── PayGlocal/              # PayGlocal integration
│   ├── PhonePe/                # PhonePe integration
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
│   ├── Omnibus/                # EU Omnibus lowest-price history
│   └── EUWithdrawal/           # EU right-of-withdrawal
├── routes/
│   ├── web.php                 # Minimal — packages define their own routes
│   └── console.php
├── tests/
│   ├── Pest.php                # Pest configuration binding test cases to packages
│   ├── Datasets/               # Datasets shared across package suites
│   └── Unit/                   # Cross-package checks; need no database
├── phpunit.xml                 # Test suites per package
├── pint.json                   # Pint config (preset: laravel)
├── vite.config.js              # Root Vite config
├── docker-compose.yml          # Laravel Sail: PHP 8.4, MySQL 8.0 / MariaDB 10.11 / PostgreSQL 16, nginx or apache, Redis, Elasticsearch 8.19, Kibana, Mailpit
├── docker/local/               # nginx and apache configs for the Sail web-server profiles
└── docker/production/          # Production images: {nginx,apache,litespeed} x {mysql,mariadb,postgres}
```

## Architecture

### Modular Package System

All core functionality lives in `packages/Webkul/`. Each package is a self-contained Laravel
package with its own models, controllers, routes, views, migrations, and service providers.
`composer.json` uses a `"type": "path"` repository for `packages/*/*`, so package code needs no
`composer update`; run `composer dump-autoload` after adding a package.

**Dual registration**: each package registers in two places:
1. **`bootstrap/providers.php`** — the main ServiceProvider (routes, views, translations, migrations, events, config)
2. **`config/concord.php`** — the ModuleServiceProvider (Konekt Concord model/enum registration), for a package with models. DebugBar, FPC, ImageCache, Installer, MagicAI, PhonePe and SocialShare have none.

### Key Design Patterns

- **Contract, Model, Proxy**: every data entity has a Contract (interface), a Model and a Proxy (e.g. `ProductProxy`, `CategoryProxy`), which lets a model be substituted without modifying core code. Always reference proxies when type-hinting across packages.
- **Repository Pattern**: all database access goes through repositories extending `Webkul\Core\Eloquent\Repository` (Prettus L5). A repository's `model()` returns the Contract class, not the Model. Never query models directly in controllers.
- **Event-Driven Extensibility**: the framework fires dot-delimited events (`catalog.product.update.after`) at key lifecycle points, in before/after pairs. Extend behaviour through listeners rather than editing another package.
- **Routes**: Admin routes run under the `web` and `admin` middleware with the `config('app.admin_url')` prefix. Shop routes run under `web` and the `shop` group, which applies the theme, locale and currency middleware.
- **22 Locales**: ar, bn, ca, de, en, es, fa, fr, he, hi_IN, id, it, ja, nl, pl, pt_BR, ro, ru, sin, tr, uk, zh_CN.

### Package Anatomy

```
packages/Webkul/<Package>/src/
├── Config/                     # system.php (admin settings), admin-menu.php, acl.php, carriers.php, etc.
├── Contracts/                  # Interfaces for each model
├── Database/
│   ├── Migrations/
│   ├── Factories/
│   └── Seeders/
├── DataGrids/                  # DataGrid classes (extend Webkul\DataGrid\DataGrid)
├── Http/
│   ├── Controllers/            # Separate Admin/ and Shop/ controller directories
│   ├── Middleware/
│   └── Requests/               # Form Request validation classes
├── Jobs/
├── Listeners/                  # Event listeners
├── Models/                     # Eloquent models + Proxy classes
├── Observers/
├── Providers/
│   ├── {Name}ServiceProvider.php
│   └── ModuleServiceProvider.php  # Concord model registration
├── Repositories/               # Prettus L5 repositories
├── Resources/
│   ├── assets/                 # JS, CSS, images (Vite-compiled)
│   ├── lang/{locale}/          # 22 locales
│   └── views/                  # Blade templates (admin/, shop/)
├── Routes/                     # admin-routes.php, shop-routes.php, api.php
└── Type/                       # (Product package) Product type classes
```

### Extension Points

- **Shipping method**: extend `Webkul\Shipping\Carriers\AbstractShipping`, configure it in `Config/carriers.php` and `Config/system.php`.
- **Payment method**: extend `Webkul\Payment\Payment\Payment`, configure it in `Config/payment-methods.php` and `Config/system.php`.
- **Product type**: extend a class in `Webkul\Product\Type`, and register it in `Config/product_types.php`.

### Frontend Assets

Admin, Shop, and Installer each have independent Vite builds. Run `npm install` and `npm run dev`/`npm run build` from within the respective package directory, never from the project root:
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
2. Add the PSR-4 namespace to the root `composer.json` autoload
3. Register the ServiceProvider in `bootstrap/providers.php`
4. Register the ModuleServiceProvider in `config/concord.php`
5. Run `composer dump-autoload && php artisan optimize:clear`

Or use `php artisan package:make Webkul/<Name>` (requires `bagisto/bagisto-package-generator`).

## Commands

### Development

```bash
composer install                # Install PHP dependencies
php artisan bagisto:install     # Full installation (migrations, seeders, assets)
php artisan serve               # Start PHP dev server
php artisan optimize:clear      # Clear all caches (run after config/code changes)
php artisan migrate             # Run migrations
php artisan db:seed             # Seed database
```

#### Laravel Sail

`docker-compose.yml` describes the optional Docker stack. `laravel/sail` is **not** a dependency of
this repository, so install it before the first run:

```bash
composer require laravel/sail --dev
./vendor/bin/sail up -d
```

The database and the web server are Compose profiles, and `.env.example` carries
`COMPOSE_PROFILES=${DB_CONNECTION}`, so the database matching `DB_CONNECTION` is the only one that
starts. Set `DB_HOST` to the same name — each server is reachable under its service name.

`COMPOSE_PROFILES` is Docker Compose's own variable, not Laravel's: Laravel ignores it, and Compose
reads the same `.env` file. It belongs there rather than on the command line because every command
needs it, not only `up` — a `down` run without the profile stops the database container but leaves
it behind. For a one-off, prefix the command instead, as in
`COMPOSE_PROFILES=pgsql ./vendor/bin/sail up -d`. `sail up --profile pgsql` does **not** work:
Compose accepts `--profile` only before the subcommand, and Sail appends arguments after it.

| Profile | Service | `DB_HOST` / `DB_PORT` |
|---|---|---|
| `mysql` | MySQL 8.0 (default) | `mysql` / `3306` |
| `mariadb` | MariaDB 10.11 | `mariadb` / `3306` |
| `pgsql` | PostgreSQL 16 | `pgsql` / `5432` |
| `nginx`, `apache` or `litespeed` | Web server on `${FORWARD_WEB_PORT:-8080}` | — |

List several profiles to combine them, e.g. `COMPOSE_PROFILES=pgsql,nginx`. Redis, Elasticsearch,
Kibana and Mailpit carry no profile and always run.

Only the container follows `DB_CONNECTION`. Every connection in `config/database.php` reads the same
`DB_HOST` and `DB_PORT`, so both have to be set to the row above by hand — and `DB_PORT=` resolves
to an empty string rather than to the connection's own default, so remove the line instead of
emptying it. Switching database also leaves the data behind: reinstall into the new server with
`sail artisan bagisto:install` and clear the cached config.

Sail's own image ships `php8.4-cli` and serves the application with `artisan serve`, so the three
web servers sit **in front** of it: they serve `public/` from the mounted project and pass everything
else to the app container. Their configuration lives in `docker/local/` and mirrors the production
vhosts, which is what makes rewrite, header and caching behaviour worth testing there. Port 80 still
reaches `artisan serve` directly, and the servers are alternatives — they share
`FORWARD_WEB_PORT`, so enable one at a time.

Mount every web-server config read-only. The OpenLiteSpeed image chowns its configuration on start,
which takes ownership of the files inside the repository when the mount is writable.

### Testing

```bash
vendor/bin/pest                                         # Run all tests
vendor/bin/pest --parallel                              # Run all tests in parallel (one process per CPU core)
vendor/bin/pest --testsuite="Admin Feature Test"        # Run a specific test suite
vendor/bin/pest --testsuite="Unit Test"                 # Cross-package checks; needs no database
vendor/bin/pest packages/Webkul/Admin/tests/Feature     # Run tests in a directory
vendor/bin/pest --filter="test name"                    # Run a single test by name
```

Test suites defined in `phpunit.xml`: Unit (cross-package, needs no database), Admin Feature, Category Unit, Core Unit, Customer Unit, DataGrid Unit, EUWithdrawal Feature, FPC Unit/Feature, Installer Feature, Omnibus Feature, PayGlocal Unit/Feature, PayU Unit/Feature, Product Unit, Razorpay Unit/Feature, Rule Unit, Sales Unit, Shipping Unit, Shop Feature, Stripe Unit/Feature, Tax Unit.

Every package that has tests is registered above. Packages without a `tests/` directory (PhonePe, Checkout, RMA, and others) have no suite — adding a `<testsuite>` for a path that does not exist makes PHPUnit error, so write the tests first.

Shared test infrastructure lives in `tests/Datasets/` (datasets registered with `sharedDataset()` so every package can `->with()` them), `packages/Webkul/Core/tests/Concerns/` (`setConfig()`, `uploadedFileWithContents()`, price assertions), `packages/Webkul/Product/tests/Concerns/ProductTestBench.php` (indexed products of every type) and `packages/Webkul/Sales/tests/Concerns/OrderTestBench.php` (orders, invoices, shipments).

Tests use **Pest 5** (PHPUnit 13) with package-specific TestCase classes bound in `tests/Pest.php`. Each package's tests live in `packages/Webkul/<Package>/tests/`.

#### Parallel test databases

Parallel runs create one database per CPU core (`{DB_DATABASE}_test_1`, `_test_2`, …) on MySQL, MariaDB and PostgreSQL alike, and do **not** re-migrate them, so a schema change leaves them stale and the failures look like broken code. Drop them, reinstall, then re-run:

```bash
# Drop parallel test databases (adjust the count to match your CPU cores)
php artisan tinker --execute="for (\$i = 1; \$i <= 6; \$i++) { try { DB::statement(\"DROP DATABASE IF EXISTS bagisto_test_{\$i}\"); } catch (\Exception \$e) {} }"

php artisan bagisto:install --no-interaction

vendor/bin/pest --parallel --no-coverage
```

### End-to-End Tests (Playwright)

Three suites — `Admin`, `Shop`, `Installer` — each run from its own package directory:

```bash
cd packages/Webkul/Admin      # or Shop, or Installer
npm install && npm run install:browsers
npm run test:e2e
```

Append flags after `--`, e.g. `npm run test:e2e -- --grep "@en"` (Installer tags specs per locale)
or `-- --shard=1/10`. `test:e2e:headed`, `:ui`, `:debug` and `:report` mirror the Playwright flags.

Admin and Shop need a running server (`php artisan serve`) and a seeded database; Installer runs
against an *uninstalled* app. The base URL comes from `APP_URL`, falling back to `BASE_URL`.

Load the `bagisto-playwright-testing` skill for anything beyond running them — the env and path
contracts, writing specs and page objects, and diagnosing a failure.

### Frontend

```bash
cd packages/Webkul/Admin && npm install && npm run build    # Admin production build
cd packages/Webkul/Shop && npm install && npm run build     # Shop production build
cd packages/Webkul/Admin && npm run dev                     # Admin dev server with HMR
cd packages/Webkul/Shop && npm run dev                      # Shop dev server with HMR
```

### Translations

When adding new translation keys, provide translations for **all 22 locales** in the package's `Resources/lang/` directory. Verify with:

```bash
php artisan bagisto:translations:check
```

## Code Style

```bash
vendor/bin/pint --dirty          # Fix changed files only
vendor/bin/pint                  # Fix all files
vendor/bin/pint --test           # Check only (CI uses this)
```

**Important:** Always run `vendor/bin/pint` on modified files after every code change before running tests or marking work as complete.

### Multi-condition control flow

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

### Comments and Docblocks

**Do not write comments inside method bodies.** The only comment this codebase wants is the docblock above a method, property or constant — not above the class. Do not narrate what a statement does, why a line was added, or what a fix changed; the code and the commit message carry that. This applies to `//` line comments and `/** */` blocks alike, and to PHP, Blade, JavaScript, and Vue.

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

- Every method and property carries a docblock whatever its visibility: a capitalised sentence ending in a full stop, at most two lines.
- In Pest files, tests are grouped under `// ====` banners with a Title Case heading; nothing else is commented.

## CI Workflows (.github/workflows/)

| Workflow | Trigger | What it does |
|----------|---------|--------------|
| `pest-tests.yml` | push, pull request | Installs Bagisto and runs `vendor/bin/pest --parallel` on PHP 8.4 × MySQL 8.0, MariaDB 10.11 and PostgreSQL 16 |
| `pint-tests.yml` | push, pull request | Runs `pint --test` (style check) |
| `playwright-tests.yml` | pull request labelled **Need Playwright Testing**, `v*` tag, manual | `installer_gate` runs the guided installer (English and Arabic × each database) and gates `playwright_tests`, which runs the Admin and Shop projects across 10 shards × each database |
| `translation-tests.yml` | push, pull request | Translation key consistency |
| `docker-publish.yml` | `v*` tag, manual | Builds and pushes the production images — {nginx, apache, litespeed} × {mysql, mariadb, postgres}, multi-arch |

All workflows run on **PHP 8.4**, which is the minimum the project requires.

## Production Docker Images

Images are built from `docker/production/` across two dimensions — web server (`nginx`, `apache`, `litespeed`) and bundled database (`mysql`, `mariadb`, `postgres`) — and published as `webkul/bagisto:<version>-<server>-<database>`.

Everything engine-specific lives in `docker/production/shared/db/<engine>/` behind a fixed contract (`engine.sh` exposing `db_default_port`, `db_connection`, `db_server_packages`, and the build init/start/wait/provision/stop and runtime ping functions). `build-install.sh` and `entrypoint.sh` source the driver rather than naming a database, so adding an engine means adding a directory, not editing the shared scripts.

[`docker/production/README.md`](docker/production/README.md) is the reference for building, tagging, publishing and running the images.

## Database Compatibility

All code must work on MySQL 8.0, MariaDB 10.11 and PostgreSQL 16. Use the existing abstractions:

- **Case-insensitive LIKE**: use `db_grammar()->caseInsensitiveLike()` instead of a hardcoded `'like'`. It returns `LIKE` on MySQL (already case-insensitive) and `ILIKE` on PostgreSQL. Use `db_grammar()->caseSensitiveLike()` when exact case matching is needed.

  ```php
  $query->where('name', db_grammar()->caseInsensitiveLike(), '%'.$search.'%');
  ```

- **Empty strings → NULL/default**: MySQL coerces `""` to `0`/`NULL`; PostgreSQL rejects it. Use model set mutators (`setXxxAttribute`), paired with `$casts` for the read side, and never sanitize in controllers.

  ```php
  public function setPriorityAttribute($value): void
  {
      $this->attributes['priority'] = $value !== '' && $value !== null ? (int) $value : 0;
  }
  ```

- **Boolean columns**: add `$casts` with `'boolean'`. For the write side, use repository validation or model mutators.
- **DB-specific SQL**: use `db_grammar()` methods (`concat`, `groupConcat`, `findInSet`, `dateFormat`, `jsonExtract`, `caseInsensitiveLike`, `caseSensitiveLike`, etc.).
- **CASE expression types**: both branches must return the same type. Use `CAST(id AS VARCHAR(255))` — `CAST(... AS CHAR)` truncates to 1 character on PostgreSQL.
- **GROUP BY**: PostgreSQL requires every non-aggregated SELECT column in GROUP BY.
- **`DB::raw()` in `updateOrCreate()`**: fails on INSERT; split into find + update/create.

## Safety Rails

- **Never modify `bootstrap/providers.php` or `config/concord.php`** without understanding the full provider chain — removing a provider breaks the entire module.
- **Translations are 22 files per key.** Missing a locale fails CI.
- **Tests must pass.** Run affected package tests after changes. Do not delete tests without approval.
- **Do not add/remove composer dependencies without approval.**
- **Do not create documentation files unless explicitly requested.**

## Validation Checklist (Before Marking Complete)

1. `vendor/bin/pint --dirty` — no style violations
2. `php artisan test --compact` — affected tests pass
3. `php artisan bagisto:translations:check` — translation keys exist in all 22 locale files (if changed)
4. No `env()` calls outside `config/` files
5. New models have Contract + Model + Proxy + Repository
6. New packages registered in `bootstrap/providers.php` and `config/concord.php`
7. Conventions from the skills above hold for every file touched — docblocks on each method and
   property, class members ordered constants → properties → constructor → public → protected →
   private, multi-clause conditions split across lines, `:` vs `::` correct in Blade

## Further Reading

- [Architecture Overview](https://devdocs.bagisto.com/architecture/overview.html)
- [Backend Architecture](https://devdocs.bagisto.com/architecture/backend.html)
- [Frontend Architecture](https://devdocs.bagisto.com/architecture/frontend.html)
- [Package Development](https://devdocs.bagisto.com/package-development/getting-started.html)
- [Shipping Method Development](https://devdocs.bagisto.com/shipping-method-development/getting-started.html)
- [Payment Method Development](https://devdocs.bagisto.com/payment-method-development/getting-started.html)
- [Product Type Development](https://devdocs.bagisto.com/product-type-development/getting-started.html)
- [Theme Development](https://devdocs.bagisto.com/theme-development/getting-started.html)
