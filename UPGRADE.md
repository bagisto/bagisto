# UPGRADE Guide

- [Upgrading To v2.6.0 From v2.5](#upgrading-to-v260-from-v25)

## Upgrading To v2.6.0 From v2.5

> [!IMPORTANT]
> **Upcoming release.** v2.6.0 is not out yet — this section covers the changes already merged into `master` that break compatibility with v2.5, and is still being added to. Changes released on the 2.5 line are documented in that branch's own upgrade guide and apply to v2.6.0 as well.
>
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Medium Impact Changes

- [The View Render Event Manager Moved To Core](#the-view-render-event-manager-moved-to-core)
- [Core No Longer Resolves Guest Groups Or Tax Categories](#core-no-longer-resolves-guest-groups-or-tax-categories)

### Low Impact Changes

- [The Queue Concerns Moved To Core](#the-queue-concerns-moved-to-core)
- [The Invoice Overdue Command Moved To Sales](#the-invoice-overdue-command-moved-to-sales)
- [Narrowed Signatures On Order And Product Types](#narrowed-signatures-on-order-and-product-types)

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

### The Queue Concerns Moved To Core

**Impact Probability: Low**

`BoundedByRetryAfter` and `RetriesOnDeadlock` are generic queue infrastructure, not import behaviour, and the index jobs that now use them sit below DataTransfer:

```php
// Before
use Webkul\DataTransfer\Jobs\Concerns\BoundedByRetryAfter;
use Webkul\DataTransfer\Jobs\Concerns\RetriesOnDeadlock;

// After
use Webkul\Core\Jobs\Concerns\BoundedByRetryAfter;
use Webkul\Core\Jobs\Concerns\RetriesOnDeadlock;
```

Both traits are otherwise unchanged: `timeoutWithinRetryAfter()` and `retryOnDeadlock()` keep their signatures and behaviour.

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
