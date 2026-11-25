# UPGRADE Guide

- [Upgrading To v2.6.0 From v2.5](#upgrading-to-v260-from-v25)
  - [Before You Start](#before-you-start)
  - [The Upgrade Itself](#the-upgrade-itself)

## Upgrading To v2.6.0 From v2.5

> [!IMPORTANT]
> **Upcoming release.** v2.6.0 is not out yet — this section covers the changes already merged into `master` that break compatibility with v2.5, and is still being added to. Changes released on the 2.5 line are documented in that branch's own upgrade guide and apply to v2.6.0 as well, except that what the 2.5 line keeps as deprecated is removed, as [What The 2.5 Line Kept For Compatibility Is Removed](#what-the-25-line-kept-for-compatibility-is-removed) lists.
>
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Medium Impact Changes

- [The View Render Event Manager Moved To Core](#the-view-render-event-manager-moved-to-core)
- [Core No Longer Resolves Guest Groups Or Tax Categories](#core-no-longer-resolves-guest-groups-or-tax-categories)
- [Cart Item Type Hints Use The Contract](#cart-item-type-hints-use-the-contract)
- [The Address Requirement Helpers Left Core](#the-address-requirement-helpers-left-core)
- [What The 2.5 Line Kept For Compatibility Is Removed](#what-the-25-line-kept-for-compatibility-is-removed)

### Low Impact Changes

- [The Deadlock Retry Concern Moved To Core](#the-deadlock-retry-concern-moved-to-core)
- [The Invoice Overdue Command Moved To Sales](#the-invoice-overdue-command-moved-to-sales)
- [Narrowed Signatures On Order And Product Types](#narrowed-signatures-on-order-and-product-types)
- [The Search Term Factory Is Singular](#the-search-term-factory-is-singular)
- [Gateway Controllers Extend Their Own Base Controller](#gateway-controllers-extend-their-own-base-controller)
- [Password Reset And Overdue Reminder Mail Is Sent By A Listener](#password-reset-and-overdue-reminder-mail-is-sent-by-a-listener)
- [The Price Indexer No Longer Takes A Catalog Rule Repository](#the-price-indexer-no-longer-takes-a-catalog-rule-repository)
- [The Theme Section Factory Moved To Theme](#the-theme-section-factory-moved-to-theme)
- [The Section Type Constants Are Removed](#the-section-type-constants-are-removed)

### Before You Start

Bagisto is distributed as a full Laravel application rather than as a package inside one, so an upgrade means bringing your copy of the application files up to the new release and reconciling anything you changed. Nothing republishes them for you.

1. **Stay on PHP 8.4.** v2.6.0 asks for nothing newer than v2.5 did, so an installation already on 8.4 needs no platform work.

2. **Back up the database.** One migration this release adds makes a search term unique within its channel and locale, and it folds the rows a term had already accumulated into one before adding the index — summing their uses and keeping the earliest row. That is not reversible from the index alone. A store that has already taken this migration on the 2.5 line will see it skipped. Two more drop the columns the 2.5 line kept for compatibility, `attribute_groups.name` and `notifications.order_id` and `read`, on a store that has them, and do nothing on one that does not.

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

### The Address Requirement Helpers Left Core

**Impact Probability: Medium**

The 2.5 line moved the three address requirement helpers to the Customer package, which owns the settings they read, and kept the `core()` methods as deprecated wrappers. Those wrappers are gone, since they made Core depend on Customer:

| Before | After |
|---|---|
| `core()->isCountryRequired()` | `\Webkul\Customer\Facades\AddressSettings::isCountryRequired()` |
| `core()->isStateRequired()` | `\Webkul\Customer\Facades\AddressSettings::isStateRequired()` |
| `core()->isPostCodeRequired()` | `\Webkul\Customer\Facades\AddressSettings::isPostCodeRequired()` |

**A theme that overrides an address view is the usual caller.** The checkout address form, the cart's shipping estimate, the account address create and edit views and the admin's Create Order address form all read these, and an override still calling `core()` fails with an undefined method error the first time it renders. The settings themselves, and what each answers, are unchanged.

---

### What The 2.5 Line Kept For Compatibility Is Removed

**Impact Probability: Medium**

A 2.5 patch release keeps the routes, methods, columns, signatures, translations and dependencies that code written for an earlier 2.5 release relies on, and deprecates them. v2.6.0 removes everything the line deprecated, whichever 2.5 release a store upgrades from, so code still using it moves to the replacements the [2.5 upgrade guide](https://github.com/bagisto/bagisto/blob/2.5/UPGRADE.md) names for each release.

**Notifications.** The three order-only routes are removed: `admin.notification.get_notification` (`get-notifications`), `admin.notification.viewed_notification` (`viewed-notifications/{orderId}`) and `admin.notification.read_all_notifications` (`read-all-notifications`). Use `admin.notification.list`, `admin.notification.open`, `admin.notification.mark_read`, `admin.notification.mark_unread` and `admin.notification.read_all`. With them go:

- `NotificationController::getNotifications()`, `viewedNotifications()` and `readAllNotifications()`;
- `NotificationRepository::getParamsData()`, `getAll()`, `countUnreadOrderNotifications()` and `findByOrder()`, replaced by `paginateFor()`, `unreadCountsFor()`, `findFor()`, `markRead()`, `markUnread()` and `markAllRead()`;
- `Notification::order()` and the `Notification::ORDER_PLACED` constant. A placed order notification carries the order's id in `data['order_id']`;
- the `notifications.order_id` and `read` columns, dropped by the migration. Each reader's read state is in `notification_reads`;
- the `admin::app.notifications.description-text`, `marked-success`, `no-record` and `read-all` keys and the `order-status-messages.*` group.

**Attribute groups.** The migration drops `attribute_groups.name`, first copying it into `admin_name` wherever that is empty. Read and write `admin_name`. `$group->name` is the group's name in the current locale, from `attribute_group_translations`, so a group created with only a `name` gets no admin name: pass `admin_name`. The family create and edit routes no longer accept `attribute_groups[...][name]`: post `attribute_groups[...][admin_name]` and `attribute_groups[...][<locale>][name]`.

**CMS pages.** `PageRepository::isUrlKeyUnique()` is removed. Call `isUrlKeyUniqueInChannels($id, $urlKey, $channelIds)`, which checks the pages on the given channels, or every page when `$channelIds` is empty.

**Signatures.** These have their return types back, so a subclass overriding one without the same type fails to load:

| Method | Returns |
|---|---|
| `Webkul\Shop\Http\Requests\Customer\AddressRequest::authorize()` | `bool` |
| `Webkul\Shop\Http\Requests\Customer\AddressRequest::rules()` and `attributes()` | `array` |
| `Webkul\Shop\Http\Controllers\API\CoreController::getCountries()` and `getStates()` | `JsonResponse` |
| `Webkul\Shop\Http\Controllers\API\CartController::crossSellProducts()` | `JsonResource` |
| `Webkul\Admin\Http\Controllers\NotificationController::index()` | `View` |
| `Webkul\Core\Console\Commands\ExchangeRateUpdate::handle()` | `int` |
| `Webkul\Notification\Providers\NotificationServiceProvider::boot()` | `void` |

`Webkul\Shop\Http\Requests\CartAddressRequest::$rules` is typed `array` again, so a subclass redeclares it as `protected array $rules`. Three constructors take their dependencies as required arguments again, so a subclass calling `parent::__construct()` passes them:

- `Webkul\Shop\Http\Controllers\API\AddressController` takes `AddressSettings` after `CustomerAddressRepository`;
- `Webkul\Admin\Http\Controllers\NotificationController` takes `TypeRegistry` and `AdminNotifications` after `NotificationRepository`;
- `Webkul\Notification\Repositories\NotificationRepository` takes `NotificationReadRepository` before the container.

`Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController` no longer takes `SectionRepository`, which it stopped using once a path that is no slug answered 404 rather than the home page, and its unused `STATUS` constant is removed. A subclass passes `CategoryRepository`, `ProductRepository` and `URLRewriteRepository` to `parent::__construct()`.

**Translations.** `shop::app.customers.account.gdpr.success-verify`, `success-verify-email-unsent` and `unable-to-sent` are removed, since nothing in core reads them. `admin::app.sales.orders.create.cart.items.search.empty-channel-info` is removed too: `empty-info` carries its message and takes the `:channel` placeholder, so an override printing `empty-info` passes the channel's name.

**QR codes.** `simplesoftwareio/simple-qrcode` is no longer installed, and `bacon/bacon-qr-code`, which draws the admin's two-factor QR code, is required at `^3.0`. A module that calls the `QrCode` facade requires a QR package in its own `composer.json`, or renders with Bacon directly:

```php
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

$svg = (new Writer(new ImageRenderer(new RendererStyle(200), new SvgImageBackEnd)))
    ->writeString($content);
```

A package that requires `simplesoftwareio/simple-qrcode` `^4` cannot be installed beside v2.6.0, since it holds Bacon to `^2`. Code that uses Bacon itself moves from 2.0 to 3.1, where `Writer`, `ImageRenderer`, `RendererStyle` and the image back ends keep their names.

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

---

### The Section Type Constants Are Removed

**Impact Probability: Low**

The section type constants on `Webkul\Theme\Models\Section` were deprecated on the 2.5 line, when theme sections became types a theme registers, and are now removed:

| Before | After |
|---|---|
| `Section::IMAGE_CAROUSEL`, `PRODUCT_CAROUSEL`, `CATEGORY_CAROUSEL`, `FOOTER_LINKS`, `STATIC_CONTENT`, `SERVICES_CONTENT` | The matching `Webkul\Theme\Enums\SectionTypeEnum` case, with `->value` where the type code is wanted |
| `Section::TYPES` | `app(\Webkul\Theme\SectionSchema::class)->types()` for the types a theme offers, or `SectionTypeEnum::getValues()` for the core codes |

Stored sections are unaffected, since the codes the constants held are the enum's values.
