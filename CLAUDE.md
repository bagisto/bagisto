# CLAUDE.md

Guidance for Claude Code working in this repository.

## Project Overview

Bagisto 2.4.x — an open-source Laravel 12 e-commerce platform. PHP 8.3+, Vue 3, Tailwind 3, Vite 5.

All functionality lives in **41 self-contained packages** under `packages/Webkul/`. The Laravel app
itself is a thin shell.

## Skills — load the relevant one before writing code, not after

The conventions live as skills, not in this file. They carry the rules `vendor/bin/pint` does not
enforce and that a reviewer will otherwise send back. Invoke them with the Skill tool.

**`bagisto-coding-standards` applies to nearly every change** — it owns code style, comments and
docblocks, Laravel idiom, data access, Blade, security and localization.

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
| Pest tests | `bagisto-pest-testing` |
| Playwright end-to-end tests | `bagisto-playwright-testing` |
| Reviewing a change | `bagisto-code-review` |
| Branching, commits, CHANGELOG, PRs | `bagisto-git-workflow` |
| The REST/GraphQL API | `bagisto-api-develop` / `bagisto-api-shop` / `bagisto-api-admin` |
| Any documentation site — developer docs, user guide, screenshots | `bagisto-documentation` |

**`bagisto-change-verification` before calling any change done** — it owns the four gates (Pint,
Pest, Playwright, translations) and the rule that a gate you did not run is a gate that failed.

Two rules worth stating here because they are so often missed:

- **A pre-existing violation in a file you touch is yours.** When you edit a class, scan its whole
  member order and docblocks and fix what is already wrong.
- **Where a skill and the surrounding code genuinely disagree, match the code** and say so in your
  summary, rather than churning the codebase either way.

## Do not edit

- `vendor/`, `node_modules/`, `composer.lock`, `package-lock.json`
- `public/themes/*/build/` — Vite output
- `storage/` — runtime caches, logs, compiled views
- `*.hot` — Vite HMR markers

## Getting it running

```bash
composer install
php artisan bagisto:install     # migrations, seeders, assets
php artisan serve
php artisan optimize:clear      # after any config or code change
```

Frontend assets build from within each package, not the root:

```bash
cd packages/Webkul/Admin && npm install && npm run build   # or Shop, or Installer
```

Run the build after **any** frontend change, then re-run the end-to-end gate — a stale bundle makes
a passing change look broken.

## The packages

```
Admin Attribute BookingProduct CartRule CatalogRule Category Checkout CMS Core Customer
DataGrid DataTransfer DebugBar EUWithdrawal FPC GDPR ImageCache Installer Inventory MagicAI
Marketing Notification PayGlocal Payment Paypal PayU PhonePe Product Razorpay RMA Rule Sales
Shipping Shop Sitemap SocialLogin SocialShare Stripe Tax Theme User
```

Each registers **twice** — its main ServiceProvider in `bootstrap/providers.php`, and its
`ModuleServiceProvider` in `config/concord.php`. Missing either half-loads the package in a way that
is hard to diagnose.

Package layout, the Contract/Model/Proxy trio, repositories, routes, ACL and menus are all covered by
`bagisto-package-development`.

## Non-negotiable

- **All database access goes through a repository.** The one sanctioned exception is a DataGrid's
  `prepareQueryBuilder()`.
- **No comments inside method bodies**, in PHP, Blade, JS or Vue. A docblock above the class, method
  or property is the only comment this codebase wants.
- **Every user-facing string goes through `trans()`**, with the key added to all **22** locales.
- **Never modify `bootstrap/providers.php` or `config/concord.php`** without understanding the
  provider chain — removing a provider breaks the module.
- **Do not add or remove a Composer or npm dependency without approval.**
- **Do not create documentation files unless asked.**
