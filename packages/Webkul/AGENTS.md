# AGENTS.md — `packages/Webkul/`

Everything in the root `AGENTS.md` still applies — commands, code style, database compatibility, boundaries and the validation checklist. This file adds what is specific to working inside a package.

## The Packages

```
packages/Webkul/
├── Admin/                  # Admin panel (controllers, views, DataGrids, reporting, e2e-pw tests)
├── Shop/                   # Customer storefront (controllers, views, e2e-pw tests)
├── Core/                   # Helpers, models, jobs, listeners, exchange rates
├── Product/                # Product models, types, indexers, repositories
├── Sales/                  # Orders, invoices, shipments, refunds
├── Checkout/               # Cart, checkout flow
├── Customer/               # Customer models, auth
├── Category/               # Category tree (nested set)
├── Attribute/              # EAV attribute system
├── Payment/                # Base payment classes (CashOnDelivery, MoneyTransfer)
├── Paypal/                 # PayPal integration
├── Stripe/                 # Stripe integration
├── Razorpay/               # Razorpay integration
├── PayU/                   # PayU integration
├── PayGlocal/              # PayGlocal integration
├── PhonePe/                # PhonePe integration
├── Shipping/               # Base shipping carriers
├── Inventory/              # Stock management
├── CartRule/               # Cart promotion rules
├── CatalogRule/            # Catalog price rules
├── Tax/                    # Tax calculation
├── DataGrid/               # Admin data table component
├── DataTransfer/           # Import/export
├── CMS/                    # CMS pages
├── Marketing/              # SEO, URL rewrites, search terms, campaigns
├── Theme/                  # Theme management
├── MagicAI/                # AI features (Laravel AI SDK)
├── Notification/           # Notifications
├── BookingProduct/         # Booking product type
├── Rule/                   # Shared rule engine base
├── User/                   # Admin user management
├── Installer/              # Installation wizard
├── SocialLogin/            # OAuth social login
├── SocialShare/            # Social sharing
├── Sitemap/                # XML sitemap generation
├── GDPR/                   # GDPR compliance
├── RMA/                    # Return merchandise authorization
├── FPC/                    # Full page cache
├── ImageCache/             # Image caching/resizing
├── DebugBar/               # Debug toolbar
├── Omnibus/                # EU Omnibus lowest-price history
└── EUWithdrawal/           # EU right-of-withdrawal
```

## Modular Package System

All core functionality lives in `packages/Webkul/`. Each package is a self-contained Laravel package with its own models, controllers, routes, views, migrations, and service providers. **The packages are autoloaded, not installed.** The root `composer.json` carries one PSR-4 entry per package — `"Webkul\\Sales\\": "packages/Webkul/Sales/src"` and 41 more — and requires none of them, so package code needs no `composer update`; adding a package means adding its namespace there and running `composer dump-autoload`. The `"type": "path"` repository for `packages/*/*` is what lets a package dropped in there be required by Composer when one has to be; nothing shipped uses it.

**Dual registration**: each package registers in two places:
- **`bootstrap/providers.php`** — the main ServiceProvider (routes, views, translations, migrations, events, config)
- **`config/concord.php`** — the ModuleServiceProvider (Konekt Concord model/enum registration), for a package with models. DebugBar, FPC, ImageCache, Installer, MagicAI, PhonePe and SocialShare have none.

## Package Anatomy

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
│   ├── lang/{locale}/          # 23 locales
│   └── views/                  # Blade templates (admin/, shop/)
├── Routes/                     # web.php, api.php (Admin and Shop split web.php further by area)
└── Type/                       # (Product package) Product type classes
```

## Naming Conventions

- **Namespace**: `Webkul\<PackageName>` (e.g., `Webkul\Product`)
- **Routes**: `Routes/web.php` per package, with `Routes/api.php` for API routes. Admin and Shop are large enough to split theirs by area — `Admin/src/Routes/web/catalog-routes.php`, `Shop/src/Routes/store-front-routes.php` — each `require`d from that package's `web.php`
- **Models**: Singular (`Product`, `Category`)
- **Repositories**: `<Model>Repository` (e.g., `ProductRepository`)
- **Controllers**: `<Model>Controller` in `Http/Controllers/Admin/` or `Shop/`

## Extension Points

- **Shipping method**: extend `Webkul\Shipping\Carriers\AbstractShipping`, configure it in `Config/carriers.php` and `Config/system.php`.
- **Payment method**: extend `Webkul\Payment\Payment\Payment`, configure it in `Config/payment-methods.php` and `Config/system.php`.
- **Product type**: extend a class in `Webkul\Product\Type`, and register it in `Config/product_types.php`.

## Frontend Assets

Admin, Shop, and Installer each have independent Vite builds. Run `npm install` and `npm run dev`/`npm run build` from within the respective package directory, never from the project root:
- **Admin**: `packages/Webkul/Admin/` builds to `public/themes/admin/default/build/`
- **Shop**: `packages/Webkul/Shop/` builds to `public/themes/shop/default/build/`
- **Installer**: `packages/Webkul/Installer/`

Vue 3 components are used within Blade templates via `@pushOnce('scripts')` / Blade component slots.

## Adding a New Package

1. Create `packages/Webkul/<Name>/src/` with the standard structure
2. Add the PSR-4 namespace to the root `composer.json` autoload
3. Register the ServiceProvider in `bootstrap/providers.php`
4. Register the ModuleServiceProvider in `config/concord.php`
5. Run `composer dump-autoload && php artisan optimize:clear`

Or use `php artisan package:make Webkul/<Name>` (requires `bagisto/bagisto-package-generator`).
