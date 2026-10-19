# AGENTS.md — Instructions for Bagisto 2.5.x

Bagisto 2.5.x is an open-source Laravel 13 e-commerce platform: PHP 8.4+, Vue.js 3, Tailwind CSS 4, Vite 6. It runs on MySQL 8.0, MariaDB 10.11 or PostgreSQL 16; all three are first-class and CI covers each.

This file is the single source of instructions for every coding agent. `CLAUDE.md` imports it, and `.github/copilot-instructions.md` points here.

## Skills — load the relevant one before writing code, not after

Conventions live as skills in `.claude/skills/<name>/SKILL.md`, git-ignored so present only where installed; read the markdown directly if your harness has no loader. **`bagisto-coding-standards` applies to nearly every change.**

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

**Load `bagisto-change-verification` before calling any change done** — it owns the gates. Where a skill and the surrounding code disagree, follow the code and say so in your summary.

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

### Testing

```bash
vendor/bin/pest                                         # Run all tests
vendor/bin/pest --parallel                              # One process per CPU core; what CI runs
vendor/bin/pest --testsuite="Admin Feature Test"        # Run one suite; phpunit.xml has the names
vendor/bin/pest --testsuite="Unit Test"                 # Cross-package checks; needs no database
vendor/bin/pest packages/Webkul/Admin/tests/Feature     # Run a directory
vendor/bin/pest --filter="test name"                    # Run a single test
php artisan test --parallel --recreate-databases        # Rebuild the worker databases
```

Pest 5 on PHPUnit 13; a package's tests live in `packages/Webkul/<Package>/tests/`. A `<testsuite>` pointing at a path that does not exist makes PHPUnit error, so write the tests first.

**The stale seeder.** Each worker's database is migrated every run, so a new migration needs nothing from you. A changed **seeder** does: `db:seed` fires only on the run that creates the database, so every worker stays on the old baseline and the failures read as broken code. Rebuild with the artisan command above — `vendor/bin/pest --recreate-databases` accepts the flag and silently does nothing, because Pest sets it in `$_ENV` and Laravel reads `$_SERVER`.

A test that prompts hangs a parallel run forever: pass `--no-interaction` to any `Artisan::call()` that can prompt. One that passes serially and fails in parallel is asserting on what a shared database cannot guarantee — a global count, an unordered list position. Fix the assertion.

### End-to-End Tests (Playwright)

```bash
cd packages/Webkul/Admin         # or Shop, or Installer
npm install
npm run install:browsers         # playwright install --with-deps chromium
npm run test:e2e                 # flags after --, e.g. -- --grep "@en" or -- --shard=1/10
npm run test:e2e:headed          # :ui, :debug and :report mirror the Playwright modes
npm run typecheck                # tsc --noEmit over the suite — run it on any spec change
npm run format:check             # prettier over tests/e2e-pw; `format` writes
```

Admin and Shop need a running server and a seeded database; Installer runs against an *uninstalled* app. The base URL comes from `APP_URL`, falling back to `BASE_URL`.

### Frontend

```bash
cd packages/Webkul/Admin && npm install && npm run build    # Admin production build
cd packages/Webkul/Shop && npm install && npm run build     # Shop production build
cd packages/Webkul/Admin && npm run dev                     # Admin dev server with HMR
cd packages/Webkul/Shop && npm run dev                      # Shop dev server with HMR
```

### Translations

A new key needs all 23 locales in the package's `Resources/lang/`. Verify with `php artisan bagisto:translations:check`.

`bagisto-pest-testing` owns the suite layout, fixtures and test benches; `bagisto-playwright-testing` owns the env and path contracts, writing specs and page objects, and diagnosing a failure.

## Repository Map

Standard Laravel, with everything of substance under `packages/Webkul/`. The paths worth knowing:

```
packages/Webkul/        # ★ All 42 packages — see packages/Webkul/AGENTS.md
app/                    # Thin shell: middleware, providers. Almost nothing lives here
bootstrap/providers.php # Every service provider registration
config/concord.php      # Concord model/proxy registrations — the second half of package wiring
config/themes.php       # Shop + Admin themes, incl. each shop theme's `customize` block
phpunit.xml             # One test suite per package; the source of truth for suite names
pint.json               # Pint config (preset: laravel)
tests/                  # Pest.php bindings, shared Datasets, cross-package Unit checks
docker/production/      # Production images: {nginx,apache,litespeed} x {mysql,mariadb,postgres}
```

## Architecture

- **Contract, Model, Proxy**: every data entity has a Contract (interface), a Model and a Proxy (e.g. `ProductProxy`, `CategoryProxy`), which lets a model be substituted without modifying core code. Always reference proxies when type-hinting across packages.
- **Repository Pattern**: all database access goes through repositories extending `Webkul\Core\Eloquent\Repository` (Prettus L5). A repository's `model()` returns the Contract class, not the Model. Never query models directly in a controller, listener, job or service — the one sanctioned exception is a DataGrid's `prepareQueryBuilder()`.
- **Event-Driven Extensibility**: the framework fires dot-delimited events (`catalog.product.update.after`) at key lifecycle points, in before/after pairs. Extend behaviour through listeners rather than editing another package.
- **Routes**: Admin routes run under the `web` and `admin` middleware with the `config('app.admin_url')` prefix. Shop routes run under `web` and the `shop` group, which applies the theme, locale and currency middleware.
- **23 Locales**: ar, bn, ca, de, en, es, fa, fr, he, hi_IN, id, it, ja, nl, pl, pt_BR, ro, ru, sin, sw, tr, uk, zh_CN.

Package structure, anatomy, naming, the extension points and how to add a package live in [`packages/Webkul/AGENTS.md`](packages/Webkul/AGENTS.md), which agents read when working in there.

## Code Style

```bash
vendor/bin/pint --dirty          # Fix changed files only
vendor/bin/pint                  # Fix all files
vendor/bin/pint --test           # Check only; what CI runs
```

Run Pint over what you changed before running tests or calling the work done. Pint settles spacing, imports and trailing commas. These are the rules it cannot see, and the ones a reviewer sends back:

- **Class members run** constants → properties → constructor → public → protected → private, each visibility one contiguous block.
- **Every method, property and constant carries a docblock** whatever its visibility: a capitalised sentence ending in a full stop, two lines at most. Never one above the class.
- **No comments inside method bodies** — not in PHP, Blade, JavaScript or Vue. A reason worth keeping goes in the method's docblock or the commit message; a body that needs prose wants a named method instead.
- **A condition with more than one `&&`/`||` clause goes multiline**, each clause on its own line with the boolean operator leading. Single-clause conditions stay inline.
- **In Pest files** tests sit under `// ====` banners with a Title Case heading, and nothing else is commented.
- **Markdown is unwrapped**: one line per paragraph, never hard-wrapped at a column. A newline inside a paragraph renders as a space, so wrapping changes nothing a reader sees while turning a one-word edit into a rewrite of every following line. Table rows, fenced code and explicit hard breaks keep their own line endings.
- **A pre-existing violation in a file you touch is yours** — scan the whole class, not your lines.

`bagisto-coding-standards` owns the detail: worked examples, Blade, Laravel idiom, data access, localization and the security checklists.

## Database Compatibility

Every query, migration and raw fragment has to work on **MySQL 8.0, MariaDB 10.11 and PostgreSQL 16** — all three are first-class and CI runs the suite against nine versions of them. Use `db_grammar()` instead of engine-specific SQL or a literal `'like'`, normalise empty strings in model set mutators rather than in controllers, and never resolve a conflict by catching a constraint violation: a failed statement aborts the whole PostgreSQL transaction, so `upsert()` is the fix. `bagisto-coding-standards` carries the rest in `database-compatibility.md`.

## CI

Every push and pull request runs `pint --test`, `vendor/bin/pest --parallel` on PHP 8.4 against nine database versions (MySQL 8.0/8.4/9.7, MariaDB 10.11/11.8/12.3, PostgreSQL 10/14/18), and the translation-key check. Playwright runs on a pull request labelled **Need Playwright Testing**, and the Docker images build on a `v*` tag. Details in `.github/workflows/`.

## Production Docker Images

Built from `docker/production/` across web server x bundled database and published as `webkul/bagisto:<version>-<server>-<database>`. Engine-specific code sits behind a fixed driver contract in `docker/production/shared/db/<engine>/`, so adding an engine means adding a directory. [`docker/production/README.md`](docker/production/README.md) is the reference.

## Boundaries

Two kinds of limit: paths a change never touches, and rails that hold whatever the task is.

### Never edit these paths

- `vendor/`, `node_modules/`, `composer.lock`, `package-lock.json`
- `public/themes/*/*/build/` — Vite output; regenerate it with `npm run build`, never hand-edit it
- `storage/` — runtime caches, logs, compiled views
- `*.hot` files — Vite HMR markers
- `packages/Webkul/*/src/Resources/assets/` — only edit if working on frontend; always run `npm run build` from the respective package directory after

### Safety rails

- **Never modify `bootstrap/providers.php` or `config/concord.php`** without understanding the full provider chain — removing a provider breaks the entire module.
- **Translations are 23 files per key.** Missing a locale fails CI.
- **Tests must pass.** Run affected package tests after changes. Do not delete tests without approval.
- **Do not add/remove composer dependencies without approval.**
- **Do not create documentation files unless explicitly requested.**

## Security Audits — Settled False Positives

Each row is a finding an audit raised and a maintainer has since settled, with the reasoning. Read it before reporting a security finding, and again before writing the report.

- **Do not re-report a row.** Leave it out, or note in one line that it is known and why.
- **A row describes the code as it stands.** If the surrounding code has changed so the reasoning no longer holds, say so and re-open the finding rather than trusting the row.
- **Only a maintainer adds a row**, after reviewing the code themselves. An agent reports what it found and lets the maintainer decide; it never promotes its own dismissal into the table.
- **Verify before dismissing anything, listed or not.** These rows exist because agents are wrong in both directions — they invent findings and they miss real ones.

| Claim | Where it appears | Why it is settled |
|---|---|---|
| The request forgery exemption covers the whole `stripe/*` prefix where only the webhook posts | `bootstrap/app.php`, `preventRequestForgery(except: ['stripe/*'])` | Deliberate. External Stripe modules register their own routes under this prefix and depend on the exemption, so narrowing it to `stripe/webhook` breaks them. The routes core ships beside the webhook are GET redirects, which the middleware does not check in any case. |
| Custom scripts reach the admin panel and the admin login page, so a role with configuration access can run JavaScript where credentials are typed | `components/layouts/anonymous.blade.php`, `components/layouts/index.blade.php`, `general.content.custom_scripts.*` | Deliberate. Custom CSS and JavaScript are a feature of the panel as much as the storefront, and the login screen shares the anonymous layout. Configuration access is treated as trusted: a role granted it can already change how the panel behaves, so granting it is the control, not the markup. |
| Trusting every proxy lets any client set `X-Forwarded-For`, so the address rate limits key on, and the address written to logs, are attacker-controlled | `bootstrap/app.php`, `trustProxies(at: '*')` | Deliberate. A store runs behind proxies the application cannot enumerate, and both HTTPS detection and the client address depend on the forwarded headers arriving intact, so the default trusts them. Restricting the list is a deployment decision, made at the edge by the operator, not a code change. |

## Before Marking Complete

- `vendor/bin/pint --dirty` — clean
- `vendor/bin/pest --compact` on the affected suites — passing
- `php artisan bagisto:translations:check` — if any key changed
- New model has Contract + Model + Proxy + Repository; new package registered in both `bootstrap/providers.php` and `config/concord.php`
- Names follow their own convention: **route names snake_case** (`admin.sales.eu_withdrawals.index`), **translation keys kebab-case** (`admin::app.eu-withdrawal.view.received-at`), **storage directories plural kebab-case** (`products/{id}/downloadable-links`), **Blade view paths kebab-case**. URL paths follow none of them, and where a view reference and its directory disagree, rename the directory

`bagisto-change-verification` owns these gates — what each one covers, and how to tell a pre-existing failure from one you caused.

## Further Reading

[devdocs.bagisto.com](https://devdocs.bagisto.com/) — architecture, package development, and the shipping, payment, product-type and theme extension guides.
