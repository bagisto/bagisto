# Storefront (Shop package) — Order Journey Bug Report

**Area tested:** the **storefront**, from product creation in the admin panel through to a placed order, for every product type — product page, add to cart, cart, checkout steps, order placement, and what the shopper and the merchant see afterwards
**Product:** Bagisto 2.5.x
**Date:** 2026-08-17
**Environment:** local install at `http://127.0.0.1:8000/` — MySQL 8, one channel, free shipping + cash on delivery + money transfer enabled

### What was covered

Each product type was created through the admin endpoints exactly as a merchant would, then bought through the storefront as a shopper:

| Type | Product page | Add to cart | Checkout | Order created | Notes |
|---|---|---|---|---|---|
| Simple | ✔ | ✔ | ✔ | ✔ | signed-in and guest |
| Virtual | ✔ | ✔ | ✔ | ✔ | signed-in and guest, no shipping step |
| Downloadable | ✔ | ✔ | ✔ signed in / ✘ guest | ✔ signed in | **issue 1** |
| Grouped | ✔ | ✔ | ✔ | ✔ | one order line per associated product |
| Bundle | ✔ | ✔ | ✔ | ✔ | bundle line recorded |
| Configurable | ✔ | ✔ | ✔ | ✔ | signed-in and guest |
| Booking (event) | ✔ | ✔ | — | — | blocked, see the note at the end |

Also exercised: stock limits at the cart and after an order, the checkout step guards (missing shipping method, a method that was never offered, an empty cart, a booking with no ticket chosen), payment-method availability per cart type, download links after purchase, and the shopper's own order pages.

### Summary

| # | Severity | Issue |
|---|---|---|
| 1 | **High** | A guest cannot buy a downloadable product — the order fails at the final click with a database error |
| 2 | **High** | A non-numeric "products per page" setting makes every category page and the product listing fail |
| 3 | Medium | Order placement answers with a server error when no payment method has been chosen |

---

## 1. [HIGH] A guest cannot buy a downloadable product — the order fails at the final click with a database error

**What happens**

A downloadable product is offered to guests like any other: the product page opens, the item goes into the cart, the address step and the payment step both succeed. Then **placing the order fails with a raw database error**:

    SQLSTATE[23000]: Integrity constraint violation: 1048
    Column 'customer_id' cannot be null … insert into `downloadable_link_purchased`

The order is rolled back (no order row is created) and **the cart is left intact**, so the shopper can only try again — and every attempt ends the same way. From their side, the shop simply refuses to take their money at the last step, with a server error page.

The same product bought by a **signed-in customer** works perfectly: the order is created, and one purchased download link is recorded.

**Why it happens**

`downloadable_link_purchased.customer_id` is declared `int unsigned` **NOT NULL** — the migration (`2019_06_21_202249_create_downloadable_link_purchased_table.php`) has no `nullable()`. `OrderRepository::create()` calls `downloadableLinkPurchasedRepository->saveLinks($orderItem, 'available')` for every downloadable item, with no customer to attach for a guest order. Nothing earlier in the flow refuses a guest, so the failure lands on the final step.

**Steps to reproduce**

1. In the admin panel create a downloadable product with one URL link, priced, enabled, guest checkout allowed.
2. On the storefront, **not signed in**, open the product, tick the link and add it to the cart.
3. Fill in the billing address, choose a payment method that is offered for a non-deliverable cart (for example Money Transfer).
4. Press **Place Order**.

**Actual result**

A server error; no order is created; the cart still holds the item.

**Expected result**

Either the shop should stop a guest before checkout for downloadable items (the way it already tells you when a product cannot be bought), or — better — the purchased-link record should allow a null customer and the "my downloads" flow should key on the order, so guests can buy downloads.

---

## 2. [HIGH] A non-numeric "products per page" setting makes every category page and the product listing fail

**What happens**

`Configuration → Catalog → Products → Storefront → Products per page` is free text with no validation. A value such as `Ten,Twenty` is stored and then handed to the storefront, which cannot cope:

    TypeError: Webkul\Product\Helpers\Toolbar::getDefaultLimit():
    Return value must be of type int, string returned   (Toolbar.php:107)

Result: **every category page returns 500**, and so does the product listing/search API (`/api/products`), while the admin panel keeps working normally.

**Why it happens** — `packages/Webkul/Product/src/Helpers/Toolbar.php`:

```php
public function getAvailableLimits(): Collection      // line 90
{
    if ($productsPerPage = core()->getConfigData('catalog.products.storefront.products_per_page')) {
        return collect(explode(',', $productsPerPage));   // ← strings
    }
    return collect([12, 24, 36, 48]);                    // ← ints, only when unset
}

public function getDefaultLimit(): int                // line 105
{
    return $this->getAvailableLimits()->first();       // ← no cast, declared : int
}
```

A numeric setting such as `12,24` survives only because PHP coerces the numeric string; any word, or a stray unit like `12, 24 items`, throws.

**Steps to reproduce**

1. Set **Products per page** to `Ten,Twenty` and save.
2. Open any category page on the storefront.

**Expected result**

The setting should be validated in the admin panel **and** the helper should be defensive — map the parts to integers, drop anything invalid and fall back to the built-in list — so a bad setting degrades instead of taking the catalogue offline.

---

## 3. [MEDIUM] Order placement answers with a server error when no payment method has been chosen

If **Place Order** is called while the cart has no payment method saved — which is what happens when the payment step was refused, or the shopper's session lost it — the response is:

    HTTP 500   {"message":"Please specify a payment method."}

The message is right; the status is not. The comparable case is handled properly: placing an order with an **empty cart** answers `200` with a redirect instruction back to the cart. A missing payment method should be treated the same way (a 4xx or a redirect), so the storefront can show the shopper the step they still need to complete — and so genuine faults are not buried among these in the error log.

---

## Checked and working correctly

- **Product pages.** Every type's page renders. A **disabled** product returns not-found, and a **configurable variant's** own URL returns not-found, so hidden products stay hidden. Home page, CMS pages and `sitemap.xml` all serve.
- **Add to cart.** Simple, virtual, downloadable (with links), grouped (per-item quantities), bundle (option products) and configurable (chosen variant) all add correctly, and each cart carries a non-zero total.
- **Stock.** With stock management on and 2 units in stock: adding 5 is refused with *"The requested quantity is not available"* and no cart is created; adding exactly 2 succeeds. After an order takes the last unit the shop offers zero and the next shopper is refused. An order **reserves** the quantity (`product_ordered_inventories`) and lowers what the storefront offers, while raw stock is deducted at invoice time — the intended two-stage behaviour.
- **Checkout guards.** A physical order with no shipping method chosen is refused; a shipping method that was never offered is refused; an empty cart is answered with a redirect and no order; a booking product added with no ticket or slot is refused and no cart is created.
- **Payment availability.** Cash on delivery is correctly withheld from carts with nothing to deliver (virtual, downloadable) and Money Transfer is offered instead — the earlier refusal I saw there was correct behaviour, not a defect.
- **Orders.** Guest and signed-in orders complete for simple, virtual and configurable; grouped orders record one line per associated product; bundle orders record a bundle line; a signed-in downloadable order creates its purchased download links and the *Downloadable Products* page in the account opens.
- **Account pages.** The shopper's order list and order view open for their own orders. (Ownership scoping across orders, invoices, addresses, wishlist, downloads, RMA and EU withdrawals was reviewed in the code and is consistently keyed on the signed-in customer.)

### Two things left unverified

- **The booking (event) journey** — the product page and the "no ticket chosen is refused" guard were confirmed, but the ticket purchase itself was not: in the final run the admin booking payload produced no booking record for the product, and I could not tell whether that was my payload or a defect before the runner stopped working (admin-side booking creation, including event tickets, was verified separately in the Catalog work).
- **A merchant opening a storefront order** — one run returned not-found for `admin.sales.orders.view` on a freshly placed guest order. That single result is unconfirmed and was not reproduced.

Both are blocked by the same thing: **the test runner cannot start any more**. `composer.json` now asks for `pestphp/pest ^5.0` with `phpunit/phpunit ^13.0` while `nunomaduro/collision` is still pinned at `^8.0`, and PHPUnit's event facade aborts during autoload:

    Fatal error: Uncaught AssertionError: assert(interface_exists($subscriberInterface))
    in vendor/phpunit/phpunit/src/Event/Facade.php:297

That is an in-progress dependency upgrade rather than a product bug, so I have left it alone — but nothing can be re-run until Collision is on a PHPUnit 13-compatible release.

---

# Storefront → My Account → Returns (RMA) — Bug Report

**Area tested:** the customer-facing **Returns / RMA** section — the returns list, the request form, submitting a request, the conversation, and cancelling, closing and reopening a request
**Product:** Bagisto 2.5.x
**Date:** 2026-08-17
**Environment:** local install at `http://127.0.0.1:8000/` — MySQL 8, RMA reasons and statuses seeded, orders placed through the storefront

### Summary

| # | Severity | Issue |
|---|---|---|
| 1 | **High** | A cancelled or declined return request permanently uses up the item's returnable quantity, so the customer can never request that item again — and the two settings meant to allow a second attempt cannot work |

Everything else I exercised in this section behaved correctly, including the parts most likely to be wrong (ownership checks, status transitions, quantity caps). Details at the end.

---

## 1. [HIGH] A cancelled or declined return request permanently uses up the item's returnable quantity

**What happens**

When a customer asks to return or cancel items, the quantity they request is booked against the order item. That booking is **never released** when the request ends in a dead state — whether the **customer cancels their own request** or **the merchant declines it**.

The result, measured on an order of 2 units:

| Step | What the returns form offers | A new request for 2 |
|---|---|---|
| Before any request | quantity available: **2** | accepted |
| After requesting 2 and **cancelling** it | item still listed, quantity available: **0** | **refused** |
| After requesting 2 and the merchant **declining** it | item still listed, quantity available: **0** | **refused** |

The refusal the shopper sees is:

    The rma qty field must not be greater than 0.

So the item is still shown in the returns form as returnable — the shopper picks it, fills in the reason, agrees to the terms, submits — and is told their quantity must not be greater than zero. There is no explanation and no way forward from that screen. In practice: **cancel your own return request once and you have forfeited your right to return that item**, for the rest of the return window.

**The two settings that are supposed to prevent this do not help**

Bagisto has exactly the settings a merchant would use here:

- `sales.rma.setting.allowed_new_rma_request_for_cancelled_request`
- `sales.rma.setting.allowed_new_rma_request_for_declined_request`

Both were set to **yes** for this test. The new request is still refused, because the block is in the quantity accounting rather than in the status check — so these two options cannot do what their names promise on the "new request" route.

**Why it happens**

`packages/Webkul/RMA/src/Helpers/Helper.php`, in `getOrderItems()`:

```php
$rmaQuantities = $this->rmaItemsRepository
    ->whereIn('order_item_id', $orderItemIds)
    ->groupBy('order_item_id')
    ->selectRaw('order_item_id, SUM(quantity) as total_quantity')
    ->pluck('total_quantity', 'order_item_id');
...
$orderItem->currentQuantity = $orderItem->qty_ordered - $rmaQuantity;
```

The sum covers **every** RMA item for that order item with no regard to the state of its parent request, so quantities held by requests that are **cancelled (status 9)** or **declined (status 7)** keep counting. The storefront then caps a new request at `min(resolutionMax, currentQuantity)` — zero — and the validator produces the "must not be greater than 0" message.

**Steps to reproduce**

1. Place an order for 2 units of a product that allows returns, and let its return window be open.
2. As the customer, go to **My Account → Returns → Request a return**, choose that order and item, quantity **2**, any reason, agree, submit. The request is created.
3. Open the request and **cancel** it.
4. Go back to **Request a return** and ask for the same item again.

**Actual result**

The item is offered, but the submission fails with *"The rma qty field must not be greater than 0."*

**Expected result**

Quantities held by cancelled and declined requests should be released, so the item becomes returnable again — governed by the two settings above. Failing that, the item should at least not be offered in the form, and the message should say why.

**One partial way out**

**Reopening** the cancelled request does work: the request goes back to *Pending* and its page opens normally. So a customer who finds that button can recover — but only for the request they cancelled, and only if they realise that reopening the old request is the way back rather than making a new one. Nothing in the "new request" flow points them there. (The code allows reopening a **declined** request on the same terms when the corresponding setting is on.)

---

## Checked and working correctly

These are the parts of the returns section most likely to hold a serious defect, and they all held up:

- **Ownership.** Every customer-facing RMA endpoint is scoped to the signed-in customer: viewing a request, cancelling, closing, reopening, reading the conversation, sending a message, and fetching the order items for the form. A request belonging to another customer answers not-found (or an empty conversation) rather than leaking anything.
- **Quantity limits on a genuine request.** The server recomputes the allowed quantity itself rather than trusting the form: a request is capped by the resolution type (`qty_invoiced − qty_refunded` for returns, `qty_ordered − qty_invoiced − qty_canceled` for cancellations) and by what other requests already hold. Asking for more than that is refused.
- **Partial returns.** Requesting 1 of 3 units leaves 2 available, and a second request for those 2 is accepted — so the accounting is deliberate, which is what makes issue 1 look like an oversight rather than a policy.
- **Item eligibility.** The form and the submit endpoint apply the same rules — the product must allow returns, be of an eligible type, and still be inside its return window — so an item that is ineligible or out of window cannot be pushed through by a crafted request.
- **Status transitions.** A customer can only ever set the three statuses their own actions imply (*Solved* on close, *Pending* on reopen, *Cancelled* on cancel); the status is hardcoded per action rather than taken from the request, so a customer cannot mark their own return *Accepted* or *Refunded*. Closing, cancelling and reopening are each gated on the current status and on the return window, and a request that is already cancelled is refused a second cancellation.
- **Conversation.** Messages are attached to the customer's own request only, file attachments are restricted to the configured MIME types and are stored under a random name, and the original file name is kept separately for display.

---

# Storefront → My Account → Profile — Bug Report

**Area tested:** the customer **Profile** page and the admin **Customer edit** screen, specifically email uniqueness when the same address is registered on more than one channel
**Product:** Bagisto 2.5.x
**Date:** 2026-08-17
**Environment:** local install at `http://127.0.0.1:8000/` — MySQL 8, two channels
**Upstream:** reported as [#11418](https://github.com/bagisto/bagisto/issues/11418) — confirmed, still open

### What was covered

Registration and profile update on two channels, the admin customer create and edit endpoints, and the database constraint that governs all four. Reproduced with Pest feature tests against the real routes, plus a control case to isolate the cause.

### Summary

| # | Severity | Issue |
|---|---|---|
| 1 | **High** | A customer cannot save their profile at all if their email also exists on another channel |
| 2 | Medium | The same gap in the admin panel — no one can edit that customer either |

---

## 1. [HIGH] A customer cannot save their profile at all if their email also exists on another channel

**What happens**

Registration deliberately allows the same email address on different channels, and the database is built for it. But the profile update validates email uniqueness **globally**, so the moment a second channel has that address, the customer can never save their profile again — not even when they change nothing about their email. Every save returns *"The email has already been taken."*

**Why it happens**

The three layers disagree:

- `packages/Webkul/Customer/src/Database/Migrations/2024_06_04_130600_make_email_unique_per_channel.php:15` **drops** the global unique index on `email` and replaces it with a composite `(email, channel_id)` index. Per-channel duplicates are an intended, supported state.
- `packages/Webkul/Shop/src/Http/Requests/Customer/RegistrationRequest.php:30` scopes correctly: `unique:customers,email,NULL,id,channel_id,<current channel>`.
- `packages/Webkul/Shop/src/Http/Requests/Customer/ProfileRequest.php:34` does **not**: `'email' => 'email|unique:customers,email,'.$id`.

The reporter refers to this as `CustomerRequest`; the actual class is `ProfileRequest`, used by `Shop/Http/Controllers/Customer/CustomerController::update`.

**Steps to reproduce**

1. Create a second channel in the admin panel.
2. Register a customer on channel A with `test@example.com`.
3. Register a second customer on channel B with the same `test@example.com`. This succeeds — it is allowed by design.
4. Sign in on channel A as the first customer and open **My Account → Profile → Edit**.
5. Change only the first name and save.

**Actual result**

`HTTP 422` — *"The email has already been taken."* The profile cannot be saved by any means.

**Expected result**

The rule should be scoped to the customer's own channel, matching registration and the composite index.

**One caution for whoever fixes it**

Scope to the **customer's own** `channel_id` (`auth()->guard('customer')->user()->channel_id`), not `core()->getCurrentChannel()`. The profile route resolves the channel from the request, so a customer reaching the site through another channel's domain would otherwise be validated against the wrong scope and could slip past the composite index.

---

## 2. [MEDIUM] The same gap in the admin panel — no one can edit that customer either

**What happens**

Admin customer **create** is channel-scoped (`Admin/Http/Controllers/Customers/CustomerController.php:85`), but admin **update** is not (`:141` — `'email' => 'required|unique:customers,email,'.$id`). So an administrator cannot save any customer whose email is reused on another channel, even leaving the email untouched. The issue report does not mention this second site.

**Steps to reproduce**

1. With the two customers from issue 1 in place, sign in to the admin panel.
2. Go to **Customers → Customers**, open the channel-A customer and press **Save** without changing anything.

**Actual result**

`HTTP 422` — *"The email has already been taken."*

**Expected result**

Same as issue 1 — scope the rule to the customer's channel.

---

## Checked and working correctly

- **Registration** on both channels behaves exactly as designed, and the composite `(email, channel_id)` index accepts the duplicate.
- **The control case passes.** An identical profile save, with the email present on no other channel, redirects normally and writes the change — which isolates the cross-channel duplicate as the sole cause rather than anything else in the form.
- **Phone uniqueness** is still globally unique in the schema, so the unscoped phone rule in the same request is correct and was left alone.

---

# Storefront → Product Page & Shared Components — Bug Report

**Area tested:** the shared storefront Blade/Vue components — tabs, dropdowns, modals, image rendering and the API-backed sections — reached through the product page, compare, wishlist, cart, checkout and the customer order view
**Product:** Bagisto 2.5.x
**Date:** 2026-08-17
**Environment:** local install at `http://127.0.0.1:8000/` — MySQL 8, one channel

### What was covered

Every storefront page returned by the router was fetched and checked; all 15 storefront `GET` API routes were exercised (all healthy — the two 401s are correct for a guest); all 80 `x-shop::` component references, every `@include`/`@extends` and every `view('shop::…')` in the Shop controllers were resolved against the filesystem; all 787 `shop::` translation keys used in Shop views were checked with `Lang::has`; and the full diff of PR **#11318** (*Feature/wcag compilance*, merged today as `8d4d8197c0`, 30 commits / 57 files) was reviewed line by line.

### Summary

| # | Severity | Issue |
|---|---|---|
| 1 | **High** | The tabs component discards every tab item — the customer order page renders blank on desktop |
| 2 | Medium | Clicking a product's star rating does nothing on desktop |
| 3 | Medium | An image that fails to load shimmers forever instead of showing as broken |
| 4 | Medium | The image cache route turns every failure into a bare 404 with no log |
| 5 | Medium | The compare page ships a raw translation key to screen readers |
| 6 | Medium | The checkout country label is not associated with its select |
| 7 | Medium | The image-search focus ring is written so it can never match |
| 8 | Low | The dropdown leaks a global listener and dereferences a ref without a guard |
| 9 | Low | Four accessible names are hardcoded English in a 30-locale app |
| 10 | Low | Two buttons wrap block content |
| 11 | Low | Four sections stay on a shimmer forever if their API call fails |
| 12 | Low | Non-lazy images never get a `src` (latent) |
| 13 | Low | A translation is interpolated into a JavaScript string literal (latent) |

---

## 1. [HIGH] The tabs component discards every tab item — the customer order page renders blank on desktop

**What happens**

`x-shop::tabs` never renders its slot, so every `<x-shop::tabs.item>` a caller passes is dropped before it reaches the DOM. The tab strip stays on its shimmer placeholder permanently — it is the final state, not a loading state.

**Why it happens**

`packages/Webkul/Shop/src/Resources/views/components/tabs/index.blade.php:3-8`:

```blade
<v-tabs position="{{ $position }}" {{ $attributes }}>
    <x-shop::shimmer.tabs />     {{-- no {{ $slot }} anywhere in the file --}}
</v-tabs>
```

Commit `5ad093603a` (in PR #11318) correctly replaced the Blade-interpolated `{{ $slot }}` inside the Vue x-template with a real `<slot></slot>`, but never added `{{ $slot }}` to the `<v-tabs>` wrapper to feed it. The Vue component builds its tab list from each item's `mounted()` hook, so with no items the `tabs` array stays empty and no tab buttons render either.

**Two call sites are affected**

- `products/view.blade.php:76` — the desktop Description / Additional Information / Reviews tabs. Below 1180px the accordion at `products/view.blade.php:243-259` is used instead and still works, so the product page looks fine on a narrow window and broken on a wide one.
- `customers/account/orders/view.blade.php:112-2173` — roughly 2,060 lines of Information, Invoices, Shipments and Refunds content. On desktop this page renders essentially nothing; only the mobile-only blocks after `</x-shop::tabs>` survive.

**Steps to reproduce**

1. Open any product page on the storefront in a window wider than 1180px.
2. Scroll to where the Description / Additional Information / Reviews tabs should be.
3. View source and search for `v-tab-item` — there are zero occurrences.
4. For the worse case: sign in as a customer with at least one order and open **My Account → Orders → View** on the same wide window.

**Actual result**

An animated shimmer that never resolves. On the order page, no order information at all.

**Expected result**

The tab items render and the tab strip is usable. Adding `{{ $slot }}` inside the `<v-tabs>` wrapper fixes this and issue 2 together.

---

## 2. [MEDIUM] Clicking a product's star rating does nothing on desktop

**What happens**

The star rating under a product's name is a button that should jump to the reviews. On desktop nothing happens at all.

**Why it happens**

`products/view.blade.php:329` binds `@click="scrollToReview"`, and the handler at `products/view.blade.php:771-790` looks for two elements:

- `#review-tab-button` — generated by the tabs `v-for`, which iterates an empty array because of issue 1. It never exists.
- `#review-accordian-button` — lives inside the mobile-only wrapper at `products/view.blade.php:161` (`1180:hidden`). On desktop it is in the DOM but `display:none`, so `querySelector` still finds it, `.click()` toggles something invisible, and `scrollIntoView` on a hidden element is a no-op.

Two smaller faults in the same handler: the `.click()` is an unconditional **toggle**, so on mobile a second click closes the reviews instead of re-scrolling; and it fires at both targets regardless of viewport.

**Steps to reproduce**

1. Open a product that already has at least one approved review (the rating only renders when `$totalRatings` is non-zero), in a window wider than 1180px.
2. Click the star rating beneath the product name.

**Actual result**

Nothing happens — no scroll, no reviews.

**Expected result**

The page scrolls to the reviews and opens them.

---

## 3. [MEDIUM] An image that fails to load shimmers forever instead of showing as broken

**What happens**

`v-shimmer-image` clears its loading state only from a successful `@load`. There is no `@error` handler, and the `<img>` is `v-show="! isLoading"` while the shimmer div is `v-if="isLoading"`. So **any** image that 404s leaves a shimmer animating permanently.

**Why it matters more than it sounds**

This is what made the earlier product-image outage look like "the page is still loading" rather than "the images are broken", and it is why that outage was hard to recognise as an outage at all. A broken-image icon would have pointed straight at the cause.

**Steps to reproduce**

1. In `packages/Webkul/Shop/src/Resources/views/components/media/images/lazy.blade.php`, note that `onLoad()` is the only thing that sets `isLoading = false`, and that neither `<img>` branch binds `@error`.
2. Point any product image at a path that does not exist (or break the image cache route), then load a page showing that product.

**Actual result**

An endless shimmer where the image should be.

**Expected result**

A failed image should clear the loading state and fall back to a placeholder or a normal broken-image, so the failure is visible.

---

## 4. [MEDIUM] The image cache route turns every failure into a bare 404 with no log

**What happens**

`packages/Webkul/ImageCache/src/Http/Controllers/ImageCacheController.php:70` wraps all image processing in `catch (Exception) { abort(404, 'Unable to process image.'); }`. The real exception is discarded and never logged, so an environment fault, a corrupt file and a genuinely missing image are indistinguishable from the outside.

It also catches `Exception` rather than `Throwable`, so `Error`s pass through inconsistently.

**Seen in practice today**

Commit `34e62e37c1` moved resizing onto Laravel 13's `Illuminate\Image`, which calls `ImageManager::usingDriver()` and therefore needs `intervention/image ^4`. With a `vendor/` tree still holding 3.11.8, every non-original size threw:

    Illuminate\Image\ImageException
    Failed to process image: Call to undefined method Intervention\Image\ImageManager::usingDriver()

`composer.json` (`^4.2`) and `composer.lock` (`4.2.1`) were both correct — only the installed tree was stale, and a `composer install` resolved it. But the only symptom visible anywhere was a 404.

**Steps to reproduce**

1. Request `/cache/large/<any valid product image path>`.
2. If processing fails for any reason, observe `404 Unable to process image` and check `storage/logs/laravel.log` — nothing is recorded.

**Expected result**

Log the caught exception. A 404 is a reasonable response; discarding the cause is not.

---

## 5. [MEDIUM] The compare page ships a raw translation key to screen readers

**What happens**

The per-product remove button on `/compare` was given `aria-label="@lang('shop::app.compare.remove')"` (commit `a470063af2`, PR #11318). That key does not exist, so Laravel echoes the key itself.

The `compare` group defines `remove-success`, `remove-error` and `remove-all-success`, but no bare `remove`. A screen reader announces the literal text "shop colon colon app dot compare dot remove" — worse than the unlabelled `<span>` it replaced.

Note that `php artisan bagisto:translations:check` passes 336/336 and does not catch this: it verifies the locale files agree with EN, not that keys referenced in Blade resolve.

**Steps to reproduce**

1. Open `http://127.0.0.1:8000/compare`.
2. View source and search for `aria-label="shop::`.

**Actual result**

`aria-label="shop::app.compare.remove"`.

**Expected result**

Add `'remove' => 'Remove'` to the `compare` group in every locale.

---

## 6. [MEDIUM] The checkout country label is not associated with its select

**What happens**

PR #11318 threaded `::for` / `::id` pairs through every field of the checkout address form. For the country field alone, `::for` was placed on the wrapper instead of the label — `checkout/onepage/address/form.blade.php:163`:

```blade
<x-shop::form.control-group class="mb-4!" ::for="controlName + '_country'">   {{-- wrapper --}}
    <x-shop::form.control-group.label class="...">                            {{-- no ::for --}}
```

Every other field in the file — first name, last name, email, VAT ID, street, state, city, postcode, phone — puts it on the label. `control-group` merges stray attributes onto its `<div>`, so the result is a meaningless `for` on a div and a label bound to nothing:

```html
<div class="mb-4 mb-4!" :for="controlName + '_country'">
  <label class="mb-2 block text-base ..."> Country </label>
</div>
```

**Steps to reproduce**

1. Put an item in the cart and go to checkout.
2. Inspect the Country field's label — it has no `for` attribute, and the surrounding `div` has a `for` instead.

**Expected result**

Move `::for` onto `<x-shop::form.control-group.label>`, as with every sibling field.

---

## 7. [MEDIUM] The image-search focus ring is written so it can never match

**What happens**

The camera button's label was given `peer-focus-visible:ring-2` and the file input it labels was changed to `peer sr-only`. But Tailwind's `peer-*` variant compiles to a **subsequent-sibling** combinator, and the label sits *before* the input in the DOM. CSS has no previous-sibling combinator, so the rule can never apply.

The rule as shipped in `public/themes/shop/default/build/assets/app-Bw7TCHHQ.css`:

    .peer-focus-visible\:ring-2:is(:where(.peer):focus-visible~*){ ... }

Source order in `search/images/index.blade.php` — label at line 17, input at line 56.

**Steps to reproduce**

1. Open the storefront home page.
2. Tab to the camera icon in the search box.

**Actual result**

No visible focus indicator — precisely the affordance the change set out to add.

**Expected result**

Either move the input before the label, or use `focus-within` on a shared parent. (The equivalent `peer-focus-visible` additions in the payment, shipping, address and profile views are ordered correctly and do work.)

---

## 8. [LOW] The dropdown leaks a global listener and dereferences a ref without a guard

**What happens**

An Escape-to-close handler was added to `components/dropdown/index.blade.php`, registered on `window` in `created()` and removed in `beforeDestroy()`. `beforeDestroy` is a **Vue 2** hook; this codebase is Vue 3, where it is `beforeUnmount`. The hook never fires, so neither the new `keydown` listener nor the pre-existing `click` listener is ever removed. (The modal changes in the same PR correctly use `beforeUnmount`, so the right form was known.)

The handler itself is also unguarded — `dropdown/index.blade.php:173-176`:

```js
this.$refs.toggleBlock.querySelector('button, a, [tabindex="0"]')?.focus();
//   ^^^^^^^^^^^^^^^^^ not optional-chained
```

The optional chaining protects the `querySelector` result but not `toggleBlock` itself. Vue 3 nulls refs on unmount, so a stale listener firing after teardown throws a `TypeError`.

**Steps to reproduce**

1. Open any page with a header dropdown (currency, locale or account).
2. In `components/dropdown/index.blade.php`, confirm `created()` adds two `window` listeners and only `beforeDestroy()` removes them.

**Expected result**

Rename the hook to `beforeUnmount` and guard the ref.

---

## 9. [LOW] Four accessible names are hardcoded English in a 30-locale app

PR #11318 added translated labels in most places and then hardcoded four:

| Label | File |
|---|---|
| `Close modal` | `components/modal/index.blade.php` |
| `Close drawer` | `components/drawer/index.blade.php` |
| `Menu` | `components/layouts/header/mobile/index.blade.php` |
| `Product details` | `components/tabs/index.blade.php` |

**Steps to reproduce** — switch the storefront to any non-English locale and inspect any modal or drawer close button; the accessible name is still English.

---

## 10. [LOW] Two buttons wrap block content

The tax-breakdown togglers at `checkout/cart/summary.blade.php:219` and `checkout/onepage/summary.blade.php:255` became `<button>` elements while still containing `<p>` children. A button's content model is phrasing content only. Browsers render it, but it fails validation and is the kind of nesting assistive tech handles inconsistently.

**Steps to reproduce** — open the cart, expand the tax breakdown, and inspect the toggler.

For balance: all 40 buttons the PR added carry an explicit `type="button"`, so there are no accidental form submissions.

---

## 11. [LOW] Four sections stay on a shimmer forever if their API call fails

`isLoading = false` is set only inside `.then()`, with a swallowing `catch`, so any API failure leaves the section shimmering with no error shown:

| File | Lines |
|---|---|
| `customers/account/wishlist/index.blade.php` | 287, 291 |
| `compare/index.blade.php` | 205, 209 |
| `checkout/cart/index.blade.php` | 502, 508 |
| `products/view/reviews.blade.php` | `get()` |

The two carousels (`components/products/carousel.blade.php:124`, `components/categories/carousel.blade.php:120`) at least `console.log(error)`, but they also never clear `isLoading`.

**Steps to reproduce**

1. Make `/api/customer/wishlist` fail (stop the DB, or block the route).
2. Open **My Account → Wishlist**.

**Actual result** — an endless shimmer, no message.

**Expected result** — clear the loading state and show an error.

Latent today: all 15 storefront `GET` API routes currently return 200.

---

## 12. [LOW] Non-lazy images never get a `src` (latent)

In `components/media/images/lazy.blade.php`, `mounted()` returns early when `lazy` is false, and the `v-else` `<img>` binds only `:data-src="src"` — never `:src`. Nothing ever copies one to the other.

This is dormant rather than live: the only caller that sets `:lazy="false"` is `components/carousel/index.blade.php:80` (`::lazy="index === 0 ? false : true"`, for the first slide), and it also passes `::srcset`, which reaches the `<img>` through `$attrs` and makes the browser load it anyway. Any future caller passing only `src` with `lazy=false` gets a permanent shimmer.

**Steps to reproduce** — add `<x-shop::media.images.lazy :lazy="false" src="…" />` with no `srcset` to any view and load the page.

---

## 13. [LOW] A translation is interpolated into a JavaScript string literal (latent)

`products/view/reviews.blade.php:62` builds the star buttons' label by dropping two unescaped `@lang()` calls inside a single-quoted JS string, inside a Vue binding:

```blade
:aria-label="'@lang('...reviews.rating') ' + rating + ' @lang('...reviews.stars')'"
```

`@lang` emits unescaped, so an apostrophe in any locale's translation terminates the string literal and breaks the Vue expression — taking the review form with it. Both keys were checked across all shipped locales and none currently contains one, so this is a trap for the next translator rather than a live break. French and Italian make it a matter of time.

---

## Checked and working correctly

- **Every storefront page** returned by the router responds 200 — home, product pages for all types, categories, CMS pages, search, compare, cart, checkout, and the customer auth pages.
- **All 15 storefront `GET` API routes** are healthy; the two 401s (`api/customer/addresses`, `api/customer/wishlist`) are correct for a guest.
- **Component and view resolution is clean.** All 80 `x-shop::` component references resolve, and every `@include`/`@extends` and `view('shop::…')` in the Shop controllers points at a file that exists — no repeat of the missing-view problem found in the admin panel.
- **No duplicate DOM ids** on any of the five pages sampled.
- **Translations** pass `bagisto:translations:check` at 336/336 locales, and of the 787 `shop::` keys used in Shop views only nine are missing — eight of which predate this work by one to three years.
- **PR #11318 also fixed six real pre-existing bugs**, which should not be lost if any of it is reverted: pagination marked *every* link as the current page (the condition was `$paginator->currentPage()`, always truthy); a stray space in the filter checkbox `id`/`for`; desktop and mobile search sharing the label target `organic-search` while neither input carried the id; `peer hidden` → `peer sr-only` in five views, where `display:none` inputs could not be focused; an `<a>` with no `href` in the carousel shimmer; and an unconfigured header-offer link producing `href=""`.
- **A slot-drop scan flagged 17 candidates and 16 were false positives** — `components/layouts/index.blade.php:129` and `components/form/index.blade.php:7,38` do emit their slots. Only the tabs component is genuine.
- **The `peer-focus-visible` additions in `configurable.blade.php`** look like issue 7 but are correct: `v-field` is vee-validate's renderless `Field`, so the peer input does become a preceding sibling at runtime.

---

# Admin → Catalog → Product Edit → Image SEO — Bug Report

**Area tested:** the new **Image SEO** drawer on the product edit screen (Catalog → Products → edit → Images → *edit* icon on an image), covering alt text, file name and Replace Image
**Product:** Bagisto 2.5.x
**Date:** 2026-08-18
**Feature commit:** `ec953d97d2` — *feat: image seo added and rma issue fixed*
**Environment:** local install at `http://127.0.0.1:8000/` — MySQL 8, default channel with two locales (`en`, `ar`)

### What was covered

The full round trip: opening the drawer, saving alt text and a file name, re-opening the page, switching locales with the product locale switcher, replacing an image, exceeding the validation limits, and the storefront rendering of the result. Reproduced with Pest feature tests hitting the real admin routes, plus direct repository calls under a faked request to isolate the locale used on each side.

The ten Pest tests shipped with the feature (`ProductImageSeoTest.php`) all pass. The bugs below are in paths those tests do not cover — every one of them was reproduced with its own failing test, which was then removed.

### Summary

| # | Severity | Issue |
|---|---|---|
| 1 | **High** | Alt text saved on any locale but the default disappears from the drawer |
| 2 | **High** | Saving the product on another locale silently overwrites that locale's alt text |
| 3 | **High** | The alt-text placeholder breaks the whole image panel in French and Italian |
| 4 | Medium | Replace Image destroys the alt text of every other locale |
| 5 | Medium | Replace Image renames the file to `-1` and back on every replace |
| 6 | Medium | An over-long alt text rejects the entire product save with nothing shown |

Bugs 1, 2 and 4 share one root cause: **the feature writes alt text to the requested locale but reads it back from the application locale.**

---

## 1. [HIGH] Alt text saved on any locale but the default disappears from the drawer

**What happens**

Set an alt text while the product locale switcher is on a non-default locale, save, and the value is written to the database correctly. Re-open the page on that same locale and the drawer is empty. To the admin the feature simply looks broken — the text they typed is gone.

**Why it happens**

The write and the read use two different locales:

- **Write** — `packages/Webkul/Product/src/Repositories/ProductMediaRepository.php:169` saves to `core()->getRequestedLocaleCode()`, which is `request()->get('locale')` (`packages/Webkul/Core/src/Core.php:315`). The product edit form has no `action`, so it posts back to the current URL including `?locale=ar`. The write locale is therefore the locale switcher's locale — correct.
- **Read** — the drawer is populated from `json_encode($product->images)`. `alt_text` is a translated attribute, so Astrotomic resolves it through `packages/Webkul/Core/src/Eloquent/TranslatableModel.php:29`, which returns `config('translatable.locale') ?: app()->getLocale()`. Nothing in the admin ever calls `app()->setLocale()` from the `?locale=` parameter — there is no admin locale middleware — so this is always `config('app.locale')`, regardless of the switcher.

So the switcher steers the write and is ignored by the read.

**Steps to reproduce**

1. Make sure the default channel has at least two locales (this install has `en` and `ar`).
2. Open **Catalog → Products** and edit any product with an image.
3. Switch the locale dropdown in the page header to **Arabic**. The URL becomes `.../edit?locale=ar`.
4. Hover the image, click the **edit** (pencil) icon to open the Image SEO drawer.
5. Type `ARABIC ALT TEXT` into **Alt Text**, click **Done**, then **Save Product**.
6. Re-open the same product, still on `?locale=ar`, and open the drawer again.

**Actual result**

The Alt Text field is empty. The database is correct — `product_image_translations` holds `ARABIC ALT TEXT` for `locale = ar` — but the edit page renders the `en` row instead (or nothing, when there is no `en` row).

**Expected result**

The drawer shows the alt text of the locale currently selected in the switcher.

---

## 2. [HIGH] Saving the product on another locale silently overwrites that locale's alt text

**What happens**

This is the destructive half of bug 1. Because the page renders the **default** locale's alt text into the hidden input, and the form posts that value back under the **switcher's** locale, simply pressing Save on a translated locale — without opening the drawer, without touching anything — copies the default locale's alt text over the translated one. The translation is lost with no warning.

**Why it happens**

The metadata is submitted from a hidden input that is deliberately kept outside the drawer so it posts whether or not the drawer was opened (`packages/Webkul/Admin/src/Resources/views/components/media/images.blade.php:425`):

```blade
:value="image.alt_text ?? ''"
```

`image.alt_text` came from the read path, i.e. the application locale. The value then goes back to `ProductMediaRepository::saveAltText()`, which writes it to the request locale. Every save on a non-default locale therefore performs a copy from the default locale into it.

`config/translatable.php` has `use_fallback => true` with `fallback_locale => en`, which makes this worse rather than better: a locale that has no alt text of its own reads the English one, so the very first save on that locale writes English into it — and it can never be told apart from a real translation afterwards.

**Steps to reproduce**

1. Give one image an English alt text (`ENGLISH ALT`) with the switcher on **English**, and save.
2. Switch to **Arabic**, set the alt text to `ARABIC ALT`, and save. The `ar` database row now holds `ARABIC ALT`.
3. Reload the edit page on `?locale=ar`. Do not open the drawer. Do not change anything.
4. Press **Save Product**.

**Actual result**

The `ar` row now holds `ENGLISH ALT`. The Arabic translation is gone. Verified end to end through `PUT admin/catalog/products/{id}?locale=ar`.

**Expected result**

Saving without editing anything leaves every locale exactly as it was.

---

## 3. [HIGH] The alt-text placeholder breaks the whole image panel in French and Italian

**What happens**

On an install whose `APP_LOCALE` is `fr` or `it`, the uploaded-image tiles in the product edit screen do not render at all. The admin cannot see, reorder, replace, delete or SEO-edit any existing image.

**Why it happens**

The placeholder is interpolated straight into a Vue binding expression as a single-quoted JS string, at `packages/Webkul/Admin/src/Resources/views/components/media/images.blade.php:468`:

```blade
:placeholder="'@lang('admin::app.components.media.images.seo.alt-text-placeholder')'"
```

`@lang` emits unescaped output. Two locales have an apostrophe in that string:

- `fr` — `Décrivez ce que montre l'image`
- `it` — `Descrivi ciò che è mostrato nell'immagine`

so the rendered attribute is:

```html
:placeholder="'Décrivez ce que montre l'image'"
```

That is not a valid JavaScript expression. The template lives in a `<script type="text/x-template">` block compiled by Vue's runtime compiler, so the `v-media-image-item` component throws a template compilation error and renders nothing.

Confirmed by rendering the Blade fragment under `app()->setLocale('fr')`.

Only this one key is affected — the other three SEO strings interpolated the same way (`file-name-placeholder`, `alt-text`, `file-name`) are apostrophe-free in all 30 locales today, so the same three call sites in `videos.blade.php:191`, `catalog/attributes/create.blade.php:268,275` and `catalog/attributes/edit.blade.php:283,291` are latent rather than broken.

**Steps to reproduce**

1. Set `APP_LOCALE=fr` in `.env` and run `php artisan optimize:clear`.
2. Open **Catalog → Products** and edit a product that has at least one image.
3. Look at the Images panel and at the browser console.

**Actual result**

No image tiles. The console reports a Vue template compilation error on `v-media-image-item`.

**Expected result**

The panel renders, with the apostrophe shown literally in the placeholder.

**Fix note**

Bind through a data property or use `json_encode()` on the translated string rather than wrapping `@lang` in quotes.

---

## 4. [MEDIUM] Replace Image destroys the alt text of every other locale

**What happens**

Using **Replace Image** inside the SEO drawer keeps the alt text of the locale you are currently on and silently drops it for every other locale.

**Why it happens**

When SEO is enabled the file input is named per image id (`images.blade.php:407`), so a replacement arrives as `images[files][<id>]` holding an `UploadedFile`. `ProductMediaRepository::upload()` sends anything that is an `UploadedFile` down the **create** branch: a brand-new `product_images` row is inserted and the old id — never removed from `$previousIds` — is deleted at the end of the method. The new row starts with no translations, and `saveAltText()` fills in only `core()->getRequestedLocaleCode()`.

The image's primary key changes too, which quietly invalidates anything that referenced it.

**Steps to reproduce**

1. Give one image an alt text on **English** and a different one on **Arabic**, saving each time.
2. With the switcher on **English**, hover the image, open the SEO drawer and click **Replace Image**.
3. Pick a different file, click **Done**, then **Save Product**.
4. Switch to **Arabic** and inspect `product_image_translations` for that product.

**Actual result**

There is no `ar` row any more. The image also has a new id.

**Expected result**

Replacing the file swaps the file only. Alt text in every locale survives, and the row keeps its id.

---

## 5. [MEDIUM] Replace Image renames the file to `-1` and back on every replace

**What happens**

Replace an image while leaving the SEO file name as it is and the stored file comes back as `blue-shoe-1.webp` instead of `blue-shoe.webp`. Replace it again and it returns to `blue-shoe.webp`, then to `blue-shoe-1.webp`, alternating on each replace. The public image URL changes every single time, which is exactly what an SEO file-name feature is supposed to stop happening.

**Why it happens**

The replacement is written **before** the old row is deleted, so when `MediaFileName::resolve()` checks whether `product/<id>/blue-shoe.webp` is free the old file is still on disk. It falls into the collision loop and returns `blue-shoe-1.webp`. Only afterwards does `upload()` walk `$previousIds` and delete the original, freeing the name it just refused to use.

**Steps to reproduce**

1. Edit a product whose image is stored as `product/<id>/blue-shoe.webp` (set the file name to `blue-shoe` in the drawer and save if needed).
2. Open the SEO drawer, click **Replace Image**, pick a new file, leave **File Name** as `blue-shoe`, click **Done**, then **Save Product**.
3. Check the image path in `product_images`.
4. Repeat step 2 twice more.

**Actual result**

`blue-shoe-1.webp`, then `blue-shoe.webp`, then `blue-shoe-1.webp`.

**Expected result**

`blue-shoe.webp` every time. The name the admin asked for should win, and a replacement should not be treated as colliding with the file it is replacing.

---

## 6. [MEDIUM] An over-long alt text rejects the entire product save with nothing shown

**What happens**

Type more than 255 characters of alt text and the whole product save is rejected. The admin is bounced back to the edit page with no error anywhere on screen, no flash message, and the alt text they typed discarded. It looks like the Save button did nothing.

**Why it happens**

`packages/Webkul/Admin/src/Http/Requests/ProductForm.php:86-87` validates `images.meta.*.alt_text` at `max:255` and `images.meta.*.file_name` at `max:150`, but:

- Neither drawer input carries a `maxlength`, so nothing stops the admin at the point of typing.
- The error is handed to vee-validate as `:initial-errors` under the key `images.meta.<id>.alt_text`, and no control registers that name — the page has only `<x-admin::form.control-group.error control-name='images.files[0]' />`. The message is present in the HTML source and rendered nowhere.

The generated message is also raw: *"The images.meta.363.alt\_text field must not be greater than 255 characters."* — it leaks the internal array path and the database id, and has no `:attribute` translation behind it, so it would still be wrong even once it is displayed.

**Steps to reproduce**

1. Edit a product with an image and open the SEO drawer.
2. Paste 256 or more characters into **Alt Text**, click **Done**, then **Save Product**.

**Actual result**

The page reloads on the edit screen. No error is visible. The product is not saved and the alt text is lost. `session('errors')` holds `images.meta.<id>.alt_text`, but only inside the `:initial-errors` attribute of the form tag.

**Expected result**

A visible error next to the Alt Text field, plus a `maxlength` so the limit cannot be exceeded in the first place. The message should name the field, not the array path.

---

## The same locale defect affects the sibling media SEO screens

Bugs 1, 2 and 4 come from a pattern repeated across the whole feature commit. Every write site uses the request locale while every read site uses the application locale:

| Screen | Write | Read |
|---|---|---|
| Product images | `ProductMediaRepository.php:169` | `json_encode($product->images)` |
| Category logo / banner | `CategoryRepository.php:354` | `catalog/categories/edit.blade.php:205,228` — `$category->logo_alt`, `$category->banner_alt` |
| Channel logo | `ChannelRepository.php:143` | `settings/channels/edit.blade.php:307` — `$channel->logo_alt` |
| Attribute option swatch | `AttributeOptionRepository.php:141` | `catalog/attributes/edit.blade.php` |

Verified for categories: writing `AR LOGO ALT` under `?locale=ar` stores it correctly, and the category edit page on `?locale=ar` renders `NULL`. None of `Category`, `Channel` or `AttributeOption` overrides `isChannelBased()`, so all of them resolve through `TranslatableModel::locale()` and read the application locale.

Fixing this in one place — reading through `core()->getRequestedLocaleCode()` on the edit screens, or setting the application locale from the request in an admin middleware — would close all four.

---

## Checked and working correctly

- All ten Pest tests shipped with the feature pass (`ProductImageSeoTest.php`, 25 assertions).
- The storefront renders the saved alt text on the gallery, and falls back to the product name — and to `<name> - 2`, `<name> - 3` for later images — when there is none. `packages/Webkul/Product/src/ProductImage.php:117-135`.
- The metadata hidden inputs are deliberately kept **outside** the drawer, so alt text and file name post whether or not the drawer was ever opened. The drawer's content is `v-if="isOpen"`, so this was the right call.
- A newly uploaded image is named after the requested file name, slugged and length-capped, and falls back to a 40-character random name. Extensions are dictated by the code, never by the supplied name, and any directory component is stripped — a file name cannot escape its directory or change the file type.
- Duplicating a product carries the alt text of every locale across, via `replicateWithTranslations()` — `packages/Webkul/Product/src/Type/AbstractType.php:336`.
- All 20 new translation keys are present in all 30 locales — `php artisan bagisto:translations:check` reports 336/336 synchronized.
- Videos correctly ignore alt text: `saveAltText()` skips any model without a translated `alt_text`, and only the file name applies.
- Image SEO is edit-only. The product create flow is a modal with no image panel, so there is nothing missing there.
- Vue refs are per image tile, so each image's drawer opens independently even with many images and while dragging to reorder.

### Left unverified

Bug 3 was proven by rendering the Blade fragment under `app()->setLocale('fr')` and reading the resulting markup, not by loading the admin panel with `APP_LOCALE=fr` in a browser. The rendered expression is unambiguously invalid JavaScript, but the exact blast radius — whether only the image tiles fail or the surrounding panel goes with them — was not observed live.
