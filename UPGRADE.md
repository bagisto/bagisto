# UPGRADE Guide

- [Upgrading To v2.6.0 From v2.5](#upgrading-to-v260-from-v25)
  - [Before You Start](#before-you-start)
  - [The Upgrade Itself](#the-upgrade-itself)

## Upgrading To v2.6.0 From v2.5

> [!IMPORTANT]
> **Upcoming release.** v2.6.0 is not out yet — this section covers the changes already merged into `master` that break compatibility with v2.5, and is still being added to. Changes released on the 2.5 line are documented in that branch's own upgrade guide and apply to v2.6.0 as well.
>
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Medium Impact Changes

- [The View Render Event Manager Moved To Core](#the-view-render-event-manager-moved-to-core)
- [Core No Longer Resolves Guest Groups Or Tax Categories](#core-no-longer-resolves-guest-groups-or-tax-categories)
- [Cart Item Type Hints Use The Contract](#cart-item-type-hints-use-the-contract)

### Low Impact Changes

- [The Deadlock Retry Concern Moved To Core](#the-deadlock-retry-concern-moved-to-core)
- [The Invoice Overdue Command Moved To Sales](#the-invoice-overdue-command-moved-to-sales)
- [Narrowed Signatures On Order And Product Types](#narrowed-signatures-on-order-and-product-types)
- [The Search Term Factory Is Singular](#the-search-term-factory-is-singular)
- [Gateway Controllers Extend Their Own Base Controller](#gateway-controllers-extend-their-own-base-controller)
- [Password Reset And Overdue Reminder Mail Is Sent By A Listener](#password-reset-and-overdue-reminder-mail-is-sent-by-a-listener)
- [The Price Indexer No Longer Takes A Catalog Rule Repository](#the-price-indexer-no-longer-takes-a-catalog-rule-repository)
- [The Theme Section Factory Moved To Theme](#the-theme-section-factory-moved-to-theme)

### Before You Start

Bagisto is distributed as a full Laravel application rather than as a package inside one, so an upgrade means bringing your copy of the application files up to the new release and reconciling anything you changed. Nothing republishes them for you.

1. **Stay on PHP 8.4.** v2.6.0 asks for nothing newer than v2.5 did, so an installation already on 8.4 needs no platform work.

2. **Back up the database.** The one migration this release adds makes a search term unique within its channel and locale, and it folds the rows a term had already accumulated into one before adding the index — summing their uses and keeping the earliest row. That is not reversible from the index alone. A store that has already taken this migration on the 2.5 line will see it skipped.

3. **Maintenance mode is optional.** Nothing in this release rewrites configuration that requests read, or moves files on disk, so a short window while the assets are rebuilt is enough. The sequence below still brackets the work with `php artisan down` and `php artisan up`, which is the safer default.

> [!WARNING]
> Do not run `php artisan bagisto:install` on an existing store. It is the fresh-install command and calls `db:wipe` followed by `migrate:fresh` — it will destroy your data. An upgrade only ever runs `php artisan migrate`.

### The Upgrade Itself

Start by bringing your application files up to the new release, reconciling anything you changed. Then run:

```bash
# Take the store down
php artisan down

# Install the dependency tree
composer install

# Run the migrations
php artisan migrate --force

# Rebuild the Admin, Shop and Installer bundles, each from its own package directory
(cd packages/Webkul/Admin && npm install && npm run build)
(cd packages/Webkul/Shop && npm install && npm run build)
(cd packages/Webkul/Installer && npm install && npm run build)

# Clear every cached config, route, view and compiled service
php artisan optimize:clear

# Bring the store back up
php artisan up
```

The bundles have to be rebuilt because this release adds Swahili to the frontend validation messages in all three. The dependency set itself is unchanged, so `npm install` over the existing `node_modules` is enough — no clean reinstall.

Then verify:

```bash
php artisan bagisto:version
php artisan migrate:status
```

`bagisto:version` should report 2.6.x, and every migration should read `Ran`. Finally load the storefront home page, a category, a product page and the admin dashboard.

---

### The View Render Event Manager Moved To Core

**Impact Probability: Medium**

`ViewRenderEventManager` is the payload every `view_render_event` listener receives, and it carries no theming behaviour — it collects template names, merges parameters and renders them. It was in the Theme package while Core, Paypal and BookingProduct all listened with it, which made the foundation depend on a feature package. It now lives beside the Blade compiler that drives the same feature:

```php
// Before
use Webkul\Theme\ViewRenderEventManager;

// After
use Webkul\Core\View\ViewRenderEventManager;
```

**Every listener that type-hints it has to be repointed.** The hint is what the container matches, so a listener left on the old name receives the new class and fails with a type error the first time that view renders.

The `view_render_event()` helper moved with it, from the Theme package's helpers into Core's, and its singleton is registered by `CoreServiceProvider` rather than `ThemeServiceProvider`. The function name, its signature and **every event name a view fires are unchanged**, so a listener that takes the payload untyped keeps working:

```php
Event::listen('bagisto.shop.layout.body.after', function ($viewRenderEventManager) {
    $viewRenderEventManager->addTemplate('my-package::scripts');
});
```

---

### Core No Longer Resolves Guest Groups Or Tax Categories

**Impact Probability: Medium**

Three helpers left the `core()` helper, because each belonged to the package that owns the records it read:

| Before | After |
|---|---|
| `core()->getGuestCustomerGroup()` | `app(\Webkul\Customer\Repositories\CustomerGroupRepository::class)->getGuestGroup()` |
| `core()->getTaxCategoryById($id)` | `$product->getTypeInstance()->getTaxCategory()`, or `TaxCategoryRepository::find($id)` |
| `Webkul\Core\Core::taxRateAsIdentifier($rate)` | Removed — it had no caller |

`CustomerGroupRepository::getGuestGroup()` memoizes the group, and `CustomerServiceProvider` registers that repository with `scoped()` so the lookup is read once per request however many consumers ask for it — the same single query the `core()` memo gave you.

What this changes for custom code:

- **`Core`'s constructor takes six repositories instead of eight.** `CustomerGroupRepository` and `TaxCategoryRepository` are gone from it. Resolve `core()` from the container, as the helper does, rather than constructing it.
- **`CustomerRepository`'s constructor takes `CustomerGroupRepository` first**, then the container. A subclass that declares its own constructor has to pass both through.
- **A Blade template that called `core()->getGuestCustomerGroup()`** needs the repository injected, for example with `@inject('customerGroupRepository', \Webkul\Customer\Repositories\CustomerGroupRepository::class)`.

`tests/Unit/CoreBoundaryTest.php` now fails any new reference from Core into a feature package, with `*Proxy` classes as the one exception. The rule is written up under Safety Rails in `AGENTS.md`.

---

### Cart Item Type Hints Use The Contract

**Impact Probability: Medium**

`validateCartItem()` and its neighbours were type-hinted on the concrete `Webkul\Checkout\Models\CartItem` in five product types and two booking helpers, while `isCartItemInactive()` three lines away already used the contract. The concrete hint defeats the substitution Concord exists for: register your own cart item model that does not extend Bagisto's and cart validation throws a `TypeError`.

Every one of them now takes `Webkul\Checkout\Contracts\CartItem`:

| Class | Method |
|---|---|
| `Product\Type\AbstractType` | `validateCartItem()`, `isCartItemInactive()` |
| `Product\Type\{Bundle, Configurable, Downloadable, Booking}` | `validateCartItem()` |
| `BookingProduct\Helpers\Booking` | `validateCartItem()`, `updateCartItemPrice()` |
| `BookingProduct\Helpers\EventTicket` | `validateCartItem()` |

`Simple` and `Virtual` already used the contract and are unchanged.

**A product type or booking helper that overrides one of these has to widen its own hint too.** PHP requires an override's parameter type to be the same or wider, so a subclass left on `Models\CartItem` is a fatal error once the parent takes the contract — the one thing in this release that fails at load rather than at runtime. Passing a cart item is otherwise unchanged, since the shipped model implements the contract.

---

### The Deadlock Retry Concern Moved To Core

**Impact Probability: Low**

`RetriesOnDeadlock` is generic queue infrastructure rather than import behaviour, and the index jobs that now use it sit below DataTransfer:

```php
// Before
use Webkul\DataTransfer\Jobs\Concerns\RetriesOnDeadlock;

// After
use Webkul\Core\Jobs\Concerns\RetriesOnDeadlock;
```

`retryOnDeadlock()` keeps its signature. What counts as a lock conflict is now the framework's own judgement rather than a pair of MySQL error codes, so the retry works on PostgreSQL and MariaDB too; `isDeadlock()` is gone with it.

`BoundedByRetryAfter` is **deleted**. A job no longer carries its own timeout — bounding how long one may run is the worker's job, so run `queue:work` with a `--timeout` below the connection's `retry_after`. The production images pass `--timeout=60` against the default `retry_after` of 90. A subclass of one of the import or index jobs that read the inherited `$timeout` has to set its own.

---

### The Invoice Overdue Command Moved To Sales

**Impact Probability: Low**

`invoice:cron` reads orders and writes invoice reminders, so it is registered and scheduled by the package that owns them:

```php
// Before
Webkul\Core\Console\Commands\InvoiceOverdueCron

// After
Webkul\Sales\Console\Commands\InvoiceOverdueCron
```

The command's signature is unchanged — it is still `invoice:cron`, still scheduled daily at 03:00 — and `SalesServiceProvider` registers it in place of `CoreServiceProvider`. Only code that referenced the class by name is affected.

---

### Narrowed Signatures On Order And Product Types

**Impact Probability: Low**

Two signatures gained a return type, each of which matters only to a subclass:

- **`Order::getTotalDueAttribute()` and `getBaseTotalDueAttribute()` declare `: float`.** A subclass overriding either has to declare it too.
- **`AbstractType::getTaxCategory()` declares `: ?TaxCategory`**, where `TaxCategory` is `Webkul\Tax\Contracts\TaxCategory` rather than the model, so a substituted model still satisfies it. A product type overriding the method has to match.

---

### The Search Term Factory Is Singular

**Impact Probability: Low**

Every other factory in the codebase is named after its model, and this one was plural:

```php
// Before
use Webkul\Marketing\Database\Factories\SearchTermsFactory;

// After
use Webkul\Marketing\Database\Factories\SearchTermFactory;
```

`SearchTerm::factory()` resolves it through the model's own `newFactory()`, so only a test or seeder that named the class directly is affected.

---

### Gateway Controllers Extend Their Own Base Controller

**Impact Probability: Low**

The Stripe, PayU and PayGlocal controllers extended `Webkul\Shop\Http\Controllers\Controller`, which carries no storefront behaviour — it is `Illuminate\Routing\Controller` plus `DispatchesJobs` and `ValidatesRequests`. A payment gateway had to depend on the storefront to inherit two framework traits. Each now extends its own package's base, the way Paypal always has:

```php
// Before, in Webkul\Stripe\Http\Controllers\StripeController
use Webkul\Shop\Http\Controllers\Controller;

// After — the class in the controller's own namespace
class StripeController extends Controller
```

Stripe and PayGlocal already shipped that base class and were not using it; PayU gained one. All four are trait-for-trait identical to the Shop base they replace, so middleware, validation, route bindings and responses are unchanged. A third-party gateway extending the Shop controller keeps working, since that class is untouched.

---

### Password Reset And Overdue Reminder Mail Is Sent By A Listener

**Impact Probability: Low**

Three domain classes named a mail class belonging to a panel, which made `Customer`, `Sales` and `User` depend on `Shop` and `Admin`. The mail classes and their views have **not** moved — the panels keep them, since that is where the templates live. What changed is who initiates the send: the domain fires an event and the panel that owns the email listens for it.

| Fired by | Event | Listened to by |
|---|---|---|
| `Customer::sendPasswordResetNotification()` | `customer.password.send_reset_link` | `Webkul\Shop\Listeners\Customer@sendResetLink` |
| `Admin::sendPasswordResetNotification()` | `admin.password.send_reset_link` | `Webkul\Admin\Listeners\Admin@sendResetLink` |
| `Invoice::sendInvoiceReminder()` | `sales.invoice.send_overdue_reminder` | `Webkul\Shop\Listeners\Invoice@sendOverdueReminder` |

The emails, their subjects, their views and their recipients are unchanged, and the three public methods keep their signatures, so a store that overrode one of them still works.

Two things to know:

- **The mail is now conditional on a listener.** If a package removes or replaces `ShopServiceProvider`'s or the admin's `EventServiceProvider` listener map without carrying these entries across, the corresponding email silently stops being sent.
- **These events are a new extension point.** Replacing a reset email no longer means overriding a model method — listen for the event, or unregister the shipped listener and register your own.

---

### The Price Indexer No Longer Takes A Catalog Rule Repository

**Impact Probability: Low**

`Webkul\Product\Helpers\Indexers\Price\AbstractType` was constructed with a `CatalogRuleProductPriceRepository` that nothing ever read — the catalog rule price is taken from the product's own `catalog_rule_prices` relation. The parameter is gone:

```php
// Before
__construct(CustomerRepository, ProductCustomerGroupPriceRepository, CatalogRuleProductPriceRepository)

// After
__construct(CustomerRepository, ProductCustomerGroupPriceRepository)
```

None of the seven shipped price indexers declared its own constructor, so they are unaffected, and catalog rule pricing behaves exactly as before. A custom indexer that declared a constructor and forwarded three arguments keeps working, since PHP ignores surplus arguments to a userland function; one that type-hinted the third parameter should drop it.

---

### The Theme Section Factory Moved To Theme

**Impact Probability: Low**

`SectionFactory` builds `Webkul\Theme\Models\Section` and references only Theme classes, yet it lived in Admin and was resolved from the Theme model's `newFactory()` — the one thing making Theme depend on the admin panel:

```php
// Before
use Webkul\Admin\Database\Factories\SectionFactory;

// After
use Webkul\Theme\Database\Factories\SectionFactory;
```

`Section::factory()` resolves it through `newFactory()`, so only code naming the class directly is affected. It was the only factory in `Webkul\Admin`, so that package no longer has a `Database/Factories` directory.
