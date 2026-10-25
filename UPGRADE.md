# UPGRADE Guide

- [Upgrading To v2.5.1 From v2.5.0](#upgrading-to-v251-from-v250)
- [Upgrading To v2.5.0 From v2.4](#upgrading-to-v250-from-v24)
  - [Before You Start](#before-you-start)
  - [The Upgrade Itself](#the-upgrade-itself)

## Upgrading To v2.5.1 From v2.5.0

> [!IMPORTANT]
> **Upcoming release.** v2.5.1 is not out yet — this section covers changes already merged into the `2.5` branch and is still being added to.
>
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Medium Impact Changes

- [The Cart Facade Delegates Its Tax And Address Work](#the-cart-facade-delegates-its-tax-and-address-work)
- [Two New Cart Events, And Two That Now Fire More Often](#two-new-cart-events-and-two-that-now-fire-more-often)
- [The Minimum Order Amount No Longer Subtracts Untaxed Tax](#the-minimum-order-amount-no-longer-subtracts-untaxed-tax)
- [The PayPal Smart Button Verifies Before It Captures](#the-paypal-smart-button-verifies-before-it-captures)
- [An Emptied Cart Is Removed In The Same Request](#an-emptied-cart-is-removed-in-the-same-request)
- [Stale Tax Is Taken Off When Its Category Goes](#stale-tax-is-taken-off-when-its-category-goes)
- [The Cart Session Key Has One Reader](#the-cart-session-key-has-one-reader)

### Low Impact Changes

- [Unreadable SVG Uploads Are Refused](#unreadable-svg-uploads-are-refused)
- [An SVG That Uses A Custom DTD Entity Is Refused](#an-svg-that-uses-a-custom-dtd-entity-is-refused)
- [ProductReviewRepository Reads Reviews By Customer](#productreviewrepository-reads-reviews-by-customer)
- [The Invoice Payment Transaction Is Created By The Controller](#the-invoice-payment-transaction-is-created-by-the-controller)
- [Total Due Discounts Cancelled Quantities](#total-due-discounts-cancelled-quantities)
- [Order Item Ownership Checks Moved Onto The Order](#order-item-ownership-checks-moved-onto-the-order)
- [A Search Term Is Unique Within A Channel And Locale](#a-search-term-is-unique-within-a-channel-and-locale)
- [Index Jobs Retry A Lock Conflict](#index-jobs-retry-a-lock-conflict)
- [The Production Images Run A Queue Worker](#the-production-images-run-a-queue-worker)
- [The PayPal Standard IPN Places The Order](#the-paypal-standard-ipn-places-the-order)
- [Cart Signatures Narrowed And `$cart` Is Protected](#cart-signatures-narrowed-and-cart-is-protected)
- [The Cart Answers Instead Of Erroring Without A Cart](#the-cart-answers-instead-of-erroring-without-a-cart)
- [A Guest Session Will Not Load A Customer's Cart](#a-guest-session-will-not-load-a-customers-cart)
- [Cart Payments Are Written Through A Repository](#cart-payments-are-written-through-a-repository)
- [Removing A Cart Only Lets Go Of That Cart](#removing-a-cart-only-lets-go-of-that-cart)
- [The Shipping Estimate Keeps The Rates It Quoted](#the-shipping-estimate-keeps-the-rates-it-quoted)
- [The Cart Survives Its Row Going Mid-Request](#the-cart-survives-its-row-going-mid-request)
- [Removing Selected Cart Items Validates Its Input](#removing-selected-cart-items-validates-its-input)
- [The Cart Update Event Carries The Written Item](#the-cart-update-event-carries-the-written-item)
- [A Buy Now Purchase No Longer Loses The Basket](#a-buy-now-purchase-no-longer-loses-the-basket)

---

### Unreadable SVG Uploads Are Refused

**Impact Probability: Low**

An SVG the sanitizer cannot parse used to be written to disk as an empty file while the upload reported success, leaving a blank image behind. `Webkul\Core\Traits\Sanitizer` now reports whether it could read the file: `sanitizeSVG()` returns `true` once the file is sanitized in place, and `false` after deleting a file it could not parse, keeping the parser's complaints in `$svgSanitizeIssues`.

Every caller refuses the upload rather than keeping the file. Saving a configuration field, a locale logo, a product attribute image or file, an image search upload or a review attachment now fails with the validation message `core::app.errors.invalid-svg`, and the TinyMCE media endpoint answers with an error. Code that drove any of these and expected an empty file to be stored now receives a validation error instead.

What this changes for custom code:

- **`Sanitizer::sanitizeSVG()` returns `bool`.** A class that overrides it has to declare the return type, and a caller that wants the old behaviour has to ignore the value deliberately.
- **Review attachments are recorded as a batch.** `ProductReviewAttachmentRepository::upload()` stores every file first and records them only once all of them are readable, so one unreadable file leaves neither rows nor files behind.
- **`ProductReviewRepository` takes `ProductReviewAttachmentRepository` in its constructor**, so resolve it through the container rather than building it by hand. Its new `createWithAttachments()` writes the review and its attachments in one transaction, and `Webkul\Shop\Http\Controllers\API\ReviewController` calls that instead of creating the review and uploading the attachments itself — the controller no longer injects `ProductReviewAttachmentRepository`.
- **Configuration saves, locale saves and product updates run in a transaction**, so a refused file rolls the rest of the save back.

---

### An SVG That Uses A Custom DTD Entity Is Refused

**Impact Probability: Low**

`enshrined/svg-sanitize` is now `^1.0`, up from `^0.22.0`, which closes a stored XSS where an SVG smuggled a `javascript:` link past the sanitizer by defining a DTD entity whose name collides with an HTML5 character reference (CVE-2026-107380). The sanitizer refuses such a file rather than cleaning it, so Bagisto refuses the upload with `core::app.errors.invalid-svg`, the same way it treats any SVG it cannot read.

The refusal is wider than the attack: **an SVG that references any custom entity is now refused**, including a harmless one.

| The SVG | Before | Now |
|---|---|---|
| No `DOCTYPE` | accepted | accepted |
| A `DOCTYPE` with no internal entities, such as the SVG 1.1 DTD | accepted | accepted |
| Declares an entity but never uses it | accepted | accepted |
| Uses an entity, such as `<!ENTITY brand "Acme">` with `&brand;` in a `<text>` | accepted | **refused** |

Nothing in Bagisto generates such a file, and few drawing tools emit one, so most catalogues are unaffected. If a store does hold one, re-save the file with the entity expanded to its literal text before uploading it again.

---

### ProductReviewRepository Reads Reviews By Customer

**Impact Probability: Low**

`ProductReviewRepository::getCustomerReview()` is now `getCustomerReviews()`, and it takes the customer rather than reading the storefront guard itself, so the repository no longer depends on there being a signed-in customer and can be called from a command, a job or an admin screen. It returns a `LengthAwarePaginator` instead of an untyped value.

```php
// Before
$reviews = $this->productReviewRepository->getCustomerReview();

// After
$reviews = $this->productReviewRepository->getCustomerReviews(
    auth()->guard('customer')->id()
);
```

---

### The Invoice Payment Transaction Is Created By The Controller

**Impact Probability: Low**

Invoicing an order from the admin with *Create Transaction* ticked used to be carried out by `Webkul\Admin\Listeners\Invoice`, which read the request inside the listener. That listener fires for every invoice, including the ones a payment gateway raises with no request behind it, so the check now sits in `Webkul\Admin\Http\Controllers\Sales\InvoiceController::store()` — the one place the form posts to — and the invoice and its transaction are written in a single database transaction.

What this changes for custom code:

- **`Webkul\Admin\Listeners\Invoice::createTransaction()` is gone**, together with the `OrderTransactionRepository` the listener took in its constructor. The listener now only sends the admin notification. `OrderTransactionRepository::createForInvoice()` builds the same row from the invoice.
- **`InvoiceController` takes `OrderTransactionRepository` in its constructor**, so resolve it through the container rather than building it by hand.
- **The invoice carried by `sales.invoice.save.after` no longer has a `can_create_transaction` attribute.** It was never a column, and setting it left the model unsaveable, so saving the invoice again in the same request failed. A listener that read it should read `request()->boolean('can_create_transaction')` itself.

```php
// Before
$this->orderTransactionRepository->create([
    'transaction_id' => md5(uniqid()),
    'status' => $invoice->state,
    'type' => $invoice->order->payment->method,
    'payment_method' => $invoice->order->payment->method,
    'order_id' => $invoice->order->id,
    'invoice_id' => $invoice->id,
    'amount' => $invoice->grand_total,
]);

// After
$this->orderTransactionRepository->createForInvoice($invoice);
```

---

### Total Due Discounts Cancelled Quantities

**Impact Probability: Low**

An order's grand total is left alone when an item is cancelled, so `grand_total - grand_total_invoiced` overstated what was still owed. `Order::getTotalDueAttribute()` and `getBaseTotalDueAttribute()` now take the cancelled quantities off that figure, each in its own currency, and both answer `0` for a cancelled order.

The storefront account page computed this in the template before, and did it wrong in two different ways: the order-currency row subtracted `base_price`, mixing the two currencies, and the base-currency row rendered a base amount with the order's currency code. Both read the accessor now. The admin order view showed the raw `base_total_due` and so disagreed with the storefront; it now agrees.

What this changes for custom code:

- **A store whose order currency differs from its base currency sees different figures** on the admin order view and the storefront account page. They were wrong before.
- **The accessors read `$order->items`**, so eager-load it where you read them in a loop.
- **A template that repeated the old arithmetic should be deleted** rather than kept in step. The cancelled amount is taken at the item price only, as it was before; tax and discount on the cancelled quantity are still not apportioned.

---

### Order Item Ownership Checks Moved Onto The Order

**Impact Probability: Low**

`InvoiceController` and `ShipmentController` each carried a protected `itemsBelongToOrder()` that answered whether every item id the request named belonged to the order being documented. That check now lives on the model, as `Order::ownsItems(array $itemIds)`, so the three sales controllers share one implementation and the refund path performs it too.

```php
// Before
if (! $this->itemsBelongToOrder($order, $itemIds)) {

// After
if (! $order->ownsItems($itemIds)) {
```

**`itemsBelongToOrder()` is gone from both controllers.** A subclass that called it has to call the model method instead; one that only overrides `store()` is unaffected.

---

### A Search Term Is Unique Within A Channel And Locale

**Impact Probability: Low**

`search_terms` carried no unique constraint, and `UpdateCreateSearchTerm` read the row before creating it, so two shoppers searching the same new term both inserted it. The counter then split across the duplicates and the admin listing showed the term twice.

A migration adds `search_terms_term_channel_locale_unique` over `(term, channel_id, locale)`. **It folds existing duplicates into one row first**, so the index can be added to a table that already has them: the earliest `id` survives, `uses` is summed, `results` is taken from the most recently updated row, the first non-empty `redirect_url` is kept and `display_in_suggested_terms` takes the highest value. The rest are deleted.

What this changes for custom code:

- **Recording a search goes through `SearchTermRepository::recordSearch()`**, which increments an existing row atomically and falls back to the increment when its insert loses the race. Code that read the term and then created it hits the constraint instead.
- **Inserting a duplicate now throws** `Illuminate\Database\UniqueConstraintViolationException`.
- **`SearchTermsFactory` appends a discriminator to the term** so it can make more than one row. A test that asserted on the bare word needs the factory's value instead.

---

### Index Jobs Retry A Lock Conflict

**Impact Probability: Low**

The price, inventory, search and catalog-rule index jobs write the same few tables from several workers at once, so the database picks one of them as the victim of a lock cycle sooner or later. Each now retries that write rather than failing the job, using the same `RetriesOnDeadlock` concern the import jobs already used.

What counts as a lock conflict is now the framework's own judgement rather than a pair of MySQL error codes, so **the retry works on PostgreSQL and MariaDB as well as MySQL** — it previously did nothing on PostgreSQL, whose deadlock is `40P01` rather than `1213`.

What this changes for custom code:

- **`RetriesOnDeadlock::isDeadlock()` is gone**, replaced by the framework's `causedByConcurrencyError()`. A class that overrode it to recognise its own error codes no longer has a hook; the framework's detector covers MySQL, MariaDB, PostgreSQL and SQLite.
- **The work a retry wraps has to be safe to run again from the top.** The index rebuilds are, because each recomputes from current state.
- **Bounding how long a job may run is the worker's job, not each job's.** Run `queue:work` with a `--timeout` below the connection's `retry_after`, or the queue hands a still-running job to a second worker. The production images pass `--timeout=60` against the default `retry_after` of 90.
- **`BoundedByRetryAfter` is deleted and no job declares `$timeout`.** The import jobs carried one derived from `retry_after`; they no longer do, so a subclass that read the inherited property has to set its own.

---

### The Production Images Run A Queue Worker

**Impact Probability: Low**

The images in `docker/production/` ran only the web server, PHP and the bundled database, so with `.env.example`'s `QUEUE_CONNECTION=sync` every queued job — order mail, the post-order price and inventory reindex, search terms, sitemap generation — ran inside the request that dispatched it.

Supervisor now also runs `queue:work --queue=default,broadcastable`, and the entrypoint sets `QUEUE_CONNECTION` to `database` after the build-time install. Pass `QUEUE_CONNECTION=sync` to the container to go back to running that work inline; the worker then idles.

This changes nothing for an installation that is not built from those images. `.env.example` still ships `sync`, which is what an installation with no worker needs: the work still happens, just inside the request.

---

### The PayPal Standard IPN Places The Order

**Impact Probability: Low**

A PayPal Standard order used to be created only when the buyer came back to the store, so a buyer who paid and closed the tab left the money at PayPal, the cart active and no order — and every one of PayPal's retries failed with a 500. `Webkul\Paypal\Helpers\Ipn` now places the order from the cart named in the notification's `invoice` field when there is none, the way the Stripe, PayU, PhonePe and PayGlocal webhooks already did, under a cache lock so the buyer's return and PayPal's retries cannot produce two orders.

**Check the configured PayPal account before upgrading.** The notification is now accepted only when its `receiver_email` or `business` matches **Configuration → Sales → Payment Methods → PayPal Standard → Business Account**, and only when `mc_currency` matches the currency the order was raised in. A store whose configured address is not the one receiving the money — the shipped default is `test@webkul.com` — had its notifications processed before and will have them refused now.

What this changes for custom code:

- **`Ipn::processIpn()` takes an `array` and returns a `Webkul\Paypal\Enums\IpnStatus`** rather than returning nothing and rethrowing. `StandardController::ipn()` answers with that status's value as JSON, and with a 500 only when PayPal should send the notification again.
- **`Ipn` takes `CartRepository`** as its second constructor argument, so resolve it through the container rather than building it by hand.
- **`payment_status` is read as a `Webkul\Paypal\Enums\PaypalPaymentStatus`**, and the protocol strings the helper compares against are constants on `Ipn` — `VALIDATION_COMMAND`, `VERIFIED_RESPONSE`, `RECURRING_TRANSACTION_TYPE` and `DEFAULT_PAYMENT_TYPE`.
- **`paypal/standard/ipn` runs in the `web` middleware group**, with request forgery prevention removed on the route, which is how every other gateway's webhook is registered. The route name is unchanged.

---

### The Cart Facade Delegates Its Tax And Address Work

**Impact Probability: Medium**

`Webkul\Checkout\Cart` had grown to carry a complete tax engine and the whole cart-address pipeline alongside its own job of holding a cart. Both have moved into collaborators — `Webkul\Checkout\Helpers\CartTax` and `Webkul\Checkout\Helpers\CartAddress` — and `Cart` now delegates to them. Every public method of the facade is unchanged and still does what it did, so storefront and admin code that goes through `Cart::` is unaffected.

What this changes for custom code:

- **The constructor's signature changed.** It now takes `CartRepository`, `CartItemRepository`, `CartPaymentRepository`, `CartAddress`, `CartTax` and `WishlistRepository`. `ProductRepository` is gone — it was never used — and `TaxCategoryRepository`, `CartAddressRepository` and `CustomerAddressRepository` moved to the collaborator that uses them. Code that builds the class by hand rather than resolving it from the container has to be updated; nothing in core does.
- **`Cart::TAX_CALCULATION_BASED_ON_SHIPPING_ORIGIN`, `..._BILLING_ADDRESS` and `..._SHIPPING_ADDRESS` still exist** and still hold the same strings. They now alias `CartTax::BASED_ON_SHIPPING_ORIGIN` and friends, so compare against either.
- **`Cart::BILLING_ADDRESS_FIELDS` and `Cart::SHIPPING_ADDRESS_FIELDS`** are new constants holding the request fields each address is built from, aliasing `CartAddress::BILLING_FIELDS` and `CartAddress::SHIPPING_FIELDS`.
- A subclass that overrode `calculateItemsTax()`, `calculateShippingTax()`, `updateOrCreateBillingAddress()`, `updateOrCreateShippingAddress()` or `customerAddressId()` still works — those remain on `Cart` as delegating methods — but the implementation it may have been copying now lives in the helper.

---

### Cart Signatures Narrowed And `$cart` Is Protected

**Impact Probability: Low**

Several `Cart` methods declared types they could not honour, and the cart property's docblock claimed a type it did not hold.

What this changes for custom code:

- **`addProduct()` returns `Contracts\Cart`** and **`updateItems()` returns `bool`.** Both previously declared `|\Exception` in the union; neither could ever return one, because both throw. Callers already wrap them in `try`/`catch`.
- **`getErrors(): array`** now declares its return type. All four of its branches already returned an array, so no call can start throwing — but a subclass overriding it without a declared return type has to add one.
- **`getOrderAmount(): float`** was `: int` over a `decimal(12,4)` column, which emitted a precision-loss deprecation on every minimum-order check. `int` to `float` is a widening, so a subclass declaring `: int` stays valid.
- **`$cart` is `protected ?Contracts\Cart`**, previously `private` with a `@var Contracts\Cart` docblock that was wrong — the property is null until a cart exists. A subclass that redeclared `private $cart` will now fatal at class load with an incompatible-visibility error rather than quietly shadowing it.

---

### The Cart Answers Instead Of Erroring Without A Cart

**Impact Probability: Low**

A group of public methods dereferenced the cart without checking it was there, so calling any of them before a cart existed raised a PHP error rather than answering. Each now answers with whatever that method already used for "nothing", and no signature changed.

What this changes for custom code:

- `getItemByProduct()` returns `null`, `setCouponCode()` and `removeCouponCode()` return `$this`, `moveToWishlist()` returns `false`, `getOrderAmount()` returns `0.0`, and `setCustomerPersonnelDetails()` does nothing.
- **The four address delegates answer too.** `customerAddressId()` returns `null` and `saveAddresses()` returns without doing anything; `updateOrCreateBillingAddress()` and `updateOrCreateShippingAddress()` throw the same `shop::app.checkout.cart.index.empty-product` exception `updateItems()` already threw, because their return types promise an address they cannot produce. They previously dereferenced the absent cart directly and raised the same null-property error as the methods above.
- Code that relied on catching `Attempt to read property … on null` or `Call to a member function … on null` from these calls has to test the return value instead. The errors they raised were never a documented contract.

---

### Two New Cart Events, And Two That Now Fire More Often

**Impact Probability: Medium**

Every cart event is now dispatched from `Webkul\Checkout\Cart`; the new helpers dispatch nothing. Two cart mutations that announced nothing now do, and two existing pairs fire on more paths than before.

What this changes for custom code:

- **`checkout.cart.destroy.before` and `checkout.cart.destroy.after`** are new, carrying the cart id, and fire from `removeCart()`. The cart's own removal previously announced nothing, while `checkout.cart.delete.*` — which names item removal — made it look as though it did.
- **`checkout.cart.validate.items.before` and `checkout.cart.validate.items.after`** are new, carrying the cart, and fire around `validateItems()`. The `before` event is the only hook that sees the cart before validation removes an invalid item or the cart itself.
- **`checkout.cart.delete.before` and `checkout.cart.delete.after` now also fire when an item moves to the wishlist.** `cartItemRepository->delete()` is reached on that path too, and a listener reconciling cart state was being skipped. A listener that assumed these events meant "removed from the cart and not kept" will now fire for a move as well.
- **`checkout.cart.calculate.shipping.tax.before` and `.after` now fire on every totals collection.** They previously fired only when the cart had a selected shipping rate, a configured shipping tax category and a category that resolved. They now mean "the shipping tax step ran" rather than "shipping tax was calculated", which is what the matching items-tax pair has always meant. A listener that assumed a shipping rate exists has to check.

---

### The Minimum Order Amount No Longer Subtracts Untaxed Tax

**Impact Probability: Medium**

`getOrderAmount()` subtracted the cart's tax from the measured amount whenever **Configuration → Sales → Order Settings → Minimum Order → Include Discount Amount** was on — including when **Include Tax To Amount** was off and no tax had been added in the first place. A cart with a 100.00 subtotal, 10.00 of tax and a 5.00 discount was measured at 85.00 instead of 95.00, so carts between those figures were refused.

With both flags on the measured amount is unchanged, because the subtraction was cancelling the addition above it. Only the "include discount, exclude tax" combination changes.

**Carts that were blocked below your configured minimum may now pass it.** Review the minimum if your store runs that combination.

---

### A Guest Session Will Not Load A Customer's Cart

**Impact Probability: Low**

`initCart()` loaded the cart named by the `cart` session key whenever that cart was active, without checking who owned it. It now also requires the cart to have no `customer_id`, so a stale key can no longer resurrect a cart that belongs to a signed-in customer for a visitor who is not signed in.

`refreshCart()` also drops the session key when the row it points at has gone, rather than leaving the key behind pointing at a deleted cart.

---

### The PayPal Smart Button Verifies Before It Captures

**Impact Probability: Medium**

The Smart Button captured the PayPal order first and checked the amount afterwards, so an order approved against a cheaper cart took the money and was then refused — leaving a charged shopper with no order. The amount and currency are now verified against the cart **before** anything is captured, and the capture is checked again afterwards.

What this changes for custom code:

- **`createOrder` and `captureOrder` return 404 when the method is disabled.** Neither endpoint checked `isAvailable()`, so an order could be placed through a switched-off payment method.
- **A failed call answers with a JSON body.** The controller used to run `json_decode()` over the exception message, which produced the gateway's own JSON for an SDK error and a literal `null` for one of Bagisto's own messages — so a shopper saw a 400 with nothing in it. A non-JSON message is now returned as `{"message": "…"}`, and the storefront shows it instead of a generic failure.
- The approved order must have status `APPROVED` and exactly one purchase unit, in the cart's own currency.

---

### Cart Payments Are Written Through A Repository

**Impact Probability: Low**

`savePaymentMethod()` deleted the existing payment row and created the replacement in two unguarded statements, with `new CartPayment` bypassing the registered proxy.

What this changes for custom code:

- **`Webkul\Checkout\Repositories\CartPaymentRepository`** is new, and the write now goes through it — so a Concord model swap for `CartPayment` is honoured.
- **`Webkul\Checkout\Models\CartPayment` declares `$fillable`** (`method`, `method_title`, `cart_id`). The model was previously fully guarded, so a repository `create()` would have thrown a `MassAssignmentException`.
- **The delete and the insert run in one transaction**, and a request with no `payment.method` returns `false` instead of throwing after the delete had already committed.

---

### An Emptied Cart Is Removed In The Same Request

**Impact Probability: Medium**

`removeItem()` deleted the item row but left the cart holding its already-loaded `items` collection, so the `validateItems()` call inside the very next `collectTotals()` counted the item it had just removed. Removing the last item therefore left an active cart row with no items in it, and the cart was only cleaned up on the following request. The two item relations are now invalidated after the delete, so the emptied cart is removed at once.

What this changes for custom code:

- **`checkout.cart.destroy.before` and `checkout.cart.destroy.after` fire in the request that empties the cart**, not the next one. A listener that assumed it had one more request in which to read the cart no longer does.
- **The two removal endpoints answer `data: null` for a cart that has gone.** `shop.api.checkout.cart.destroy` and `shop.api.checkout.cart.destroy_selected` returned a fully serialised resource for a cart with nothing in it; they now match what `index` already returned, and the storefront reads the empty state from it.
- **`getErrors()` reports `CART_NOT_FOUND` rather than `MINIMUM_ORDER_AMOUNT`** after the last item goes, because there is no longer a zero-total cart left to measure against the minimum.

---

### Stale Tax Is Taken Off When Its Category Goes

**Impact Probability: Medium**

`CartTax` skipped an item whose tax category no longer resolved, leaving `applied_tax_rate`, `tax_percent` and both tax amounts at whatever the last successful pass had written. Because the totals are summed from the item rows after they are reloaded, that dead tax kept being added to `tax_total` and `grand_total` on every collection. Deleting a tax category therefore left carts quietly charging a tax that no longer existed, and the same held for the selected shipping rate.

An item or shipping rate the configured categories no longer resolve is now explicitly untaxed: the rate is cleared, the amounts are zeroed, and the tax-inclusive fields are brought back in line with their net counterparts. An item that was never taxed is left alone rather than written on every pass.

Two further cases are covered. An item's own tax category, its product's and the store's are each tried in turn, and the first that **still resolves** is used — so a deleted category pinned on the item or the product falls through to the next rather than untaxing an item another category would still tax. And the shipping rate's tax is reset before every recalculation, so a rate that stops applying — the address moved to a region the shipping category has no rate for, say — drops its tax the way an item already did, not only when the category itself is deleted.

**Check the totals of any cart whose tax category you have deleted.** They will fall by the tax that was being carried.

---

### Removing A Cart Only Lets Go Of That Cart

**Impact Probability: Low**

`removeCart()` takes the cart to delete as an argument but then cleared the held cart and the `cart` session key unconditionally, whichever cart had been passed. Merging a guest cart on login ends by removing the *guest* cart, so a successful merge left the facade holding nothing while the merged cart was alive and active — and a later `addProduct()` in the same request would start a second active cart for the customer.

Each reset is now conditional on naming the cart being removed: the held cart is dropped only when it is that cart, and the session key only when it points at it. Every caller in core passes either the held cart or one it had just loaded, so nothing else changes.

This also stops `Cart::removeCart()` from an admin screen clearing `session('cart')` in the operator's own storefront session.

---

### The Shipping Estimate Keeps The Rates It Quoted

**Impact Probability: Low**

`estimateShippingMethods()` injects an unsaved address into the cart's `billing_address` and `shipping_address` relations so a destination can be quoted without storing it. Collecting the totals replaces the cart instance, which discarded those relations, so the `Shipping::collectRates()` call that built the response then deleted the existing rate rows and wrote none back. The cart was left with a shipping method and a shipping amount but no rates, and the next collection dropped shipping out of the grand total while still showing the method as chosen.

The address is re-applied after the totals are collected, so the rates survive and the quoted total holds. A custom controller doing the same `setRelation()` trick needs the same re-application after any `Cart::collectTotals()`.

---

### The Cart Survives Its Row Going Mid-Request

**Impact Probability: Low**

A cart row can be deleted between one statement and the next — by a listener, by another request, or by the cart's own validation emptying it. Several methods assumed it could not be.

What this changes for custom code:

- **`collectTotals()` returns early** instead of raising `Attempt to assign property … on null` when the reload part way through finds the row gone. It still returns `$this`.
- **`addProduct()` throws the translated empty-cart message** rather than a `TypeError` from its own return type. A `TypeError` is not an `Exception`, so `catch (\Exception $e)` around an add — as the cart merge and both reorder loops use — did not catch it.
- **`activateCart()` does nothing for an id that resolves to no cart**, instead of throwing `ModelNotFoundException`. Note that `deActivateCart()` is terminal for a guest: it drops the only handle the session had on the cart.
- **`removeItem()` asks the database whether the item is still there**, so removing the same id twice returns `false` the second time instead of raising a PHP error from inside the repository.
- **`OnepageController::storeAddress()` sends the shopper to sign in** rather than erroring when the cart has gone before the address is posted.

---

### Removing Selected Cart Items Validates Its Input

**Impact Probability: Low**

`shop.api.checkout.cart.destroy_selected` looped over `ids` from the request without validating it, so a request with no `ids`, with `ids` that was not a list, or with the same id twice ran the loop anyway — the duplicate removing nothing on its second pass while the endpoint still answered `200`. It now validates `ids` as a required array of **distinct** integers and answers 422, so a repeated id is a validation error rather than a success over a no-op.

The storefront only offers the control when at least one item is selected, and never names the same item twice, so no interface change follows. A custom client that relied on an empty, absent, or duplicate-bearing `ids` being accepted has to stop sending it.

---

### The Cart Session Key Has One Reader

**Impact Probability: Medium**

The `cart` session key had two readers that disagreed. `initCart()` accepted the cart it names only when the row resolved, was active, and had no `customer_id`. `mergeCart()` checked only that the row existed. Nothing ever removed a key whose guard had failed, so a stale key survived every request that met it.

That gap let a login take a cart it should not have. With the key naming a cart that already belonged to another customer, `mergeCart()` fell through to `adoptGuestCart()` and stamped the logging-in customer onto it — **one customer's cart, with their items in it, became another's.** With the key naming a cart an order had already been raised from, the purchased lines were merged back into the new cart. The PayPal IPN is a live route to that state: it runs under the `web` middleware, so it gets its own throwaway session and its `Cart::deActivateCart()` clears *its* session while the buyer's browser keeps the key.

Both readers now go through one guard, which accepts strictly less than `initCart()` did before and forgets the key on every other outcome, so a dead key heals on the request that meets it.

What this changes for custom code:

- **`Webkul\Checkout\Cart::sessionCart()` and `sessionCartId()`** are new `protected` methods. A subclass that overrode the session read by other means should move onto them.
- **A key holding anything other than the object the cart writes is discarded** rather than dereferenced. Reading `->id` off, say, an integer previously threw an `ErrorException` from inside the constructor — before any of the class's own `session()->forget('cart')` lines could run — so the visitor got a 500 on every storefront page until the cookie was cleared. No core writer produces that shape; only third-party code did.
- **`mergeCart()` returns without merging** when the key fails the guard, instead of adopting or merging the cart it names.

This completes [A Guest Session Will Not Load A Customer's Cart](#a-guest-session-will-not-load-a-customers-cart), which fixed the load path only.

---

### The Cart Update Event Carries The Written Item

**Impact Probability: Low**

`updateItems()` dispatched `checkout.cart.update.before` and `checkout.cart.update.after` with the same object. The quantity had been assigned to it, but the repository `update()` that follows fills a *different* instance and its return value was discarded — so both events received one item carrying the new quantity beside the pre-update `total`, `base_total`, `total_incl_tax`, `base_total_incl_tax`, `total_weight`, `base_total_weight` and `additional`. A listener could not tell the two events apart. The row itself was always written correctly.

`.after` now receives the instance the repository wrote. No core listener subscribes to either event, so core behaviour is unchanged; an extension that expected `.after` to hand back the same object it saw in `.before` will now get a different one, carrying the saved values.

`.before` is unchanged and still carries the item with the new quantity assigned but not yet saved, which is what `isItemHaveQuantity()` validates against inventory.

---

### A Buy Now Purchase No Longer Loses The Basket

**Impact Probability: Low**

Buy Now deactivated whatever was in the cart and started a fresh one for the single product. Nothing ever reactivated the displaced cart: `initCart()`'s customer branch requires `is_active = 1`, and its guest branch needs the `cart` session key that `deActivateCart()` drops. **The shopper's basket became unreachable the moment they clicked Buy Now**, whether they completed that purchase, cancelled at the gateway, or simply walked away.

This is new behaviour rather than a restored regression. A reactivation mechanism existed in 1.x, but `9de537ee1a` (September 2023, shipped in v2.0.0) deleted the only caller of the code that parked the displaced id, so the key was never written in any 2.x release and the surviving reader was a silent no-op. `0b284cbabd` later deleted the already-dead remainder.

What this changes for custom code:

- **`Webkul\Checkout\Cart::parkCart()`** is new. It deactivates the current cart exactly as `deActivateCart()` did — which it still delegates to, so an override is honoured — parks that cart's id in the session and marks the row. The storefront's Buy Now path calls it in place of `deActivateCart()`. The basket already parked keeps its place, so a second Buy Now before the first is finished cannot displace the real basket; the first Buy Now's cart is folded into that basket rather than stranded, so its product is not lost either.
- **`Webkul\Checkout\Cart::restoreParkedCart()`** is new. It does nothing when a cart is already held, so it can never restore a basket over a cart in use, nor leave a customer holding two active carts.
- **`Webkul\Checkout\Http\Middleware\RestoreParkedCart`** is new. It calls the restore before the request reads the cart, so a page that renders its own cart state sees the restored one, and it returns immediately when no basket is parked — the ordinary case — so it costs a session lookup. It is registered two ways: appended to the **`shop` middleware group** after `Currency`, and aliased as **`cart.restore`** by `CheckoutServiceProvider` so any other entry point — a headless storefront, an API package — can apply it to its own routes. **A store that re-registers the `shop` group must include it**, or the basket is only handed back on a cart read.
- **`destroy()` and `destroySelected()` also restore, after collecting the totals**, so a shopper who empties the buy-now cart is answered with the basket rather than an empty cart they have to reload to escape. A removal with no basket parked is unaffected and still answers `data: null`.
- **`Webkul\Checkout\Cart::mergeParkedCart()`** is new, and the storefront cart page — `Webkul\Shop\Http\Controllers\CartController::index()`, the full page the shopper navigates to — calls it first. Where the shopper still has a buy-now cart *and* a basket is parked, the basket's lines are merged into it through the same path a login merge uses, and the basket row is removed. So a shopper who leaves a Buy Now unfinished and opens their cart sees both products, rather than the Buy Now product alone.
- **The merge is seated at the cart page on purpose, not at the API the mini-cart reads.** The header mini-cart fetches `shop.api.checkout.cart.index` in the background from every storefront page, so merging there folded the basket into a Buy Now cart the shopper was still checking out — and the order then charged for both. The merge now runs only when the shopper loads the cart page itself, which the Buy Now checkout never does: it reads its own summary from `shop.checkout.onepage.summary`. **Do not move this call into the mini-cart read or into middleware**: the 1.x mechanism sat in middleware and charged a mid-checkout shopper for both products. A regression test pins it.
- **`Webkul\Checkout\Cart::discardBuyNowCart()`** is new. When a Buy Now add fails — the product is out of stock, say — the storefront undoes the aborted purchase: it drops the empty cart the Buy Now started and hands the parked basket straight back, so a failed Buy Now does not leave the shopper looking at an empty cart until their next page view.
- **The merge does not carry the basket's coupon**, exactly as a guest cart's coupon is not carried over on login. A coupon belongs to the cart it was validated against, and a cart rule restricted by customer group is only re-evaluated for rules that still apply — so a code moved onto another cart can linger with no discount behind it and still have its usage consumed when the order is placed. The shopper re-applies it, which revalidates it properly.
- **`Cart::PARKED_CART_KEY`** (`parked_cart_id`) is the session key, a top-level plain integer. It is cleared whenever it is read, including on a refusal, so it cannot go stale within a session.
- **A signed-in shopper's parked basket is also marked on its own row, so it survives a logout.** Logging out invalidates the session and drops the key, which would strand a customer's parked basket as an inactive row nothing could reach. A migration adds a nullable `is_parked` boolean to `cart`; `parkCart()` sets it on the parked row and `mergeCart()` rehydrates the pointer from it at the next login, so a customer who parks a basket, logs out and logs back in still gets it back at the cart page. A guest's basket rides the session as before. The flag is cleared when the cart is reactivated and goes with the row when a merge removes it.
- **A basket parked by a guest who then signs in is handed to that customer**, through the same `adoptGuestCart()` path a login merge uses. A basket belonging to a different customer is refused.
- **The ten other `deActivateCart()` calls are untouched.** All of them run after the order row exists, and deactivation there is correct and stays terminal — a parked cart is inactive and unreachable by `initCart()`, so it cannot become an order while parked.

Two limits worth knowing:

- **A non-Shop entry point has to opt in.** The `cart.restore` alias covers the restore; the merge is `Cart::mergeParkedCart()`, to be called wherever that client serves its own cart. Nothing is wired for it automatically, because core cannot know those routes.
- **A cancelled gateway return lands on the cart page**, which is a cart display, so the basket is merged in there and the shopper sees both products. Nothing is lost on a cancel.

## Upgrading To v2.5.0 From v2.4

### High Impact Changes

- [Laravel 13 and PHP 8.4](#laravel-13-and-php-84)
- [Configuration Files You Maintain Yourself](#configuration-files-you-maintain-yourself)
- [Search Architecture Refactored to Engine-Agnostic Design](#search-architecture-refactored-to-engine-agnostic-design)
- [Tailwind CSS Upgraded from v3 to v4](#tailwind-css-upgraded-from-v3-to-v4)

### Medium Impact Changes

- [Relocated Configuration Codes](#relocated-configuration-codes)
- [Storefront Breadcrumbs Moved Into the Shop Package](#storefront-breadcrumbs-moved-into-the-shop-package)
- [Storage Directories Renamed](#storage-directories-renamed)
- [Route Names Are snake_case](#route-names-are-snake_case)
- [Translation Keys Are kebab-case](#translation-keys-are-kebab-case)
- [Theme Section Media Are Stored As Bare Paths](#theme-section-media-are-stored-as-bare-paths)
- [Image Processing Moved to Laravel's Image Component](#image-processing-moved-to-laravels-image-component)
- [Remote Storage Drivers](#remote-storage-drivers)
- [PostgreSQL Support](#postgresql-support)

### Low Impact Changes

- [The Omnibus Package](#the-omnibus-package)
- [The Phone Field Is Named Phone Everywhere](#the-phone-field-is-named-phone-everywhere)
- [Blade View Paths Are kebab-case](#blade-view-paths-are-kebab-case)
- [Product Image Size and Placeholder Settings Removed](#product-image-size-and-placeholder-settings-removed)
- [Snake_case Methods Renamed on the Core Helper](#snake_case-methods-renamed-on-the-core-helper)
- [CoreConfigRepository Helpers Renamed](#coreconfigrepository-helpers-renamed)
- [Catalog Rule Jobs No Longer Declare a Batch Size](#catalog-rule-jobs-no-longer-declare-a-batch-size)
- [Magic AI Model Lists Follow the Providers' Current Models](#magic-ai-model-lists-follow-the-providers-current-models)

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Before You Start

Bagisto is distributed as a full Laravel application rather than as a package inside one, so an upgrade means bringing your copy of the application files up to the new release and reconciling anything you changed. Nothing republishes them for you.

1. **Move to PHP 8.4.** Part of the dependency tree now requires it, so `composer install` aborts on 8.3 rather than resolving to older packages.

2. **Back up the database and `storage/`.** Several of this release's migrations rewrite existing rows in place — they rename codes in `core_config`, move Magic AI settings off retired models, rename two RMA statuses, and move uploads into their new directories. Where a `down()` exists it reverses the shape, not a snapshot, and the Magic AI one cannot be reversed at all.

3. **Plan for maintenance mode.** The migrations rewrite configuration that requests read and move uploads on disk, so the store should be down for the duration. The sequence below opens with `php artisan down` and closes with `php artisan up`.

> [!WARNING]
> Do not run `php artisan bagisto:install` on an existing store. It is the fresh-install command and calls `db:wipe` followed by `migrate:fresh` — it will destroy your data. An upgrade only ever runs `php artisan migrate`.

### The Upgrade Itself

Start by bringing your application files up to the new release, reconciling anything you changed. The root files that carry changes are `composer.json`, `composer.lock`, `.env.example`, `artisan`, `public/index.php`, `bootstrap/app.php`, `bootstrap/providers.php`, `phpunit.xml`, `config/` and `database/migrations/`. [Configuration Files You Maintain Yourself](#configuration-files-you-maintain-yourself) covers `config/` file by file.

Then run:

```bash
# Take the store down
php artisan down

# Install the new dependency tree
composer install

# Run the migrations
php artisan migrate --force

# Rebuild the Admin, Shop and Installer bundles, each from its own package directory
(cd packages/Webkul/Admin && rm -rf node_modules package-lock.json && npm install && npm run build)
(cd packages/Webkul/Shop && rm -rf node_modules package-lock.json && npm install && npm run build)
(cd packages/Webkul/Installer && rm -rf node_modules package-lock.json && npm install && npm run build)

# Clear every cached config, route, view and compiled service
php artisan optimize:clear

# Bring the store back up
php artisan up
```

Tailwind 4 changes the dependency set rather than extending it, which is why each bundle is rebuilt from a clean `node_modules` rather than installed over the old one.

If you run Elasticsearch, rebuild the product index afterwards — the flag name changed, see [Search Architecture Refactored to Engine-Agnostic Design](#search-architecture-refactored-to-engine-agnostic-design). A cluster that needs credentials has to have them re-entered in the admin first, as [Elasticsearch connection settings are now recorded in the admin](#elasticsearch-connection-settings-are-now-recorded-in-the-admin) explains, or the reindex cannot reach it:

```bash
php artisan indexer:index --type=search --mode=full
```

Then verify:

```bash
php artisan bagisto:version
php artisan migrate:status
```

`bagisto:version` should report 2.5.x, and every migration should read `Ran`. Finally load the storefront home page, a category, a product page and the admin dashboard. The home page is worth loading first: it renders the theme sections whose stored paths the migrations rewrote.

---

### Laravel 13 and PHP 8.4

**Impact Probability: High**

Bagisto v2.5 runs on Laravel 13 and requires **PHP 8.4**. The committed `composer.lock` resolves on PHP 8.4.1 or newer, below 8.5.

```diff
- "php": ">=8.3 <8.5",
- "laravel/framework": "^12.0",
+ "php": "^8.4",
+ "laravel/framework": "^13.0",
```

#### Dependencies that moved with it

| Package | v2.4 | v2.5 |
|---------|------|------|
| `laravel/framework` | `^12.0` | `^13.0` |
| `laravel/tinker` | `^2.10` | `^3.0` |
| `laravel/ai` | `^0.2.2` | `^0.7.0` |
| `kalnoy/nestedset` | `^6.0` | `^7.0` |
| `prettus/l5-repository` | `^2.6` | `^4.0` |
| `konekt/concord` | `^1.16` | `^1.18` |
| `intervention/image` | `^2.4\|^3.0` | `^4.2` |
| `spatie/laravel-responsecache` | `^7.4` | `^8.4` |
| `spatie/laravel-sitemap` | `^7.3` | `^8.0` |
| `pestphp/pest` | `^3.0` | `^5.0` |
| `pestphp/pest-plugin-laravel` | `^3.0` | `^5.0` |
| `phpunit/phpunit` | `^11.0` | `^13.0` |
| `barryvdh/laravel-debugbar` | `^3.8` | `^4.3` |

One dependency is new: `league/flysystem-aws-s3-v3` (`^3.0`), which backs the S3 and Cloudflare R2 disks described under [Remote Storage Drivers](#remote-storage-drivers).

If you depend on any of these directly, check their own upgrade notes — the majors here are not drop-in. Bagisto's own Magic AI code needed no change for the `laravel/ai` bump, but that library is still pre-1.0, so custom code written against it should be re-checked.

#### PHP 8.4: implicitly nullable parameters

PHP 8.4 deprecates an implicitly nullable parameter — a typed parameter defaulting to `null` without the type being nullable. Bagisto's own code is clean, but custom packages often are not:

```diff
- public function handle(Product $product, string $locale = null)
+ public function handle(Product $product, ?string $locale = null)
```

#### Entry-point stubs

`artisan` and `public/index.php` had stayed on the pre-Laravel-11 shape and are now the current ones. If you have not customised them, copy the versions from this release. If you have, the change is that the HTTP and console kernels are no longer resolved by hand:

```diff
- $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
- $status = $kernel->handle($input = new ArgvInput, new ConsoleOutput);
- $kernel->terminate($input, $status);
+ $status = $app->handleCommand(new ArgvInput);
```

#### Renamed names that still answer to the old ones

Laravel 13 renamed a handful of things Bagisto's skeleton files use. Laravel keeps the old spelling working for every one of them — as a config fallback, a deprecated method or a subclass — so these are tidying rather than a break. They are listed so a file you maintain yourself can be brought in line.

> [!WARNING]
> Bagisto's own SMTP transport, `Webkul\Core\Mail\Transport\DynamicMailTransport`, still reads `mail.mailers.smtp.encryption`, which the new `config/mail.php` no longer defines. If you take the new file and your encryption came only from `MAIL_ENCRYPTION`, set it at `Configuration → Emails → SMTP`, or the mailer connects without TLS.

| Where | Old | New |
|---|---|---|
| `config/logging.php` | `channels.daily.days` | `max_files` |
| `config/logging.php` | `channels.stderr.with` | `handler_with` |
| `config/mail.php` | `mailers.smtp.encryption` | `scheme` |
| `config/services.php` | `postmark.token` | `key` |
| `bootstrap/app.php` | `$middleware->validateCsrfTokens(...)` | `$middleware->preventRequestForgery(...)` |
| `config/sanctum.php` | `Illuminate\Foundation\Http\Middleware\ValidateCsrfToken` | `...\PreventRequestForgery` |

Options Laravel 13 added are now present rather than implied: the `deferred`, `background` and `failover` queue connections, the `storage` and `failover` cache stores, redis retry backoff and `persistent`, a sqlite `transaction_mode`, a `monthly` log channel, `retry_after` on the failover and roundrobin mailers, and `session.serialization`.

> [!IMPORTANT]
> `session.serialization` is set to `php`, not the `json` that a fresh Laravel 13 application defaults to. `json` is the safer choice — it closes off gadget-chain attacks if `APP_KEY` leaks — but it cannot read sessions written the old way. Switch it once you can accept every session being invalidated.

---

### Configuration Files You Maintain Yourself

**Impact Probability: High**

`config/` ships as part of the application, so an upgrade does not touch it — your files stay exactly as they are. Most of the changes below are additive and cost you nothing if you skip them, but two are not: `config/responsecache.php` is a different shape now, and `config/image.php` no longer has a reader.

| File | What changed | If you skip it |
|---|---|---|
| `config/responsecache.php` | Restructured for responsecache v8 — see the key map below | **Full Page Cache breaks.** Bagisto falls back to `responsecache.cache.lifetime_in_seconds` when no lifetime is saved at `Configuration → Cache Management`, and that key does not exist in the old shape, so cached responses are given a lifetime of zero |
| `config/image.php` → `config/images.php` | The driver setting moved to the file the framework reads, and the key changed | **Image processing falls back to `gd`** whatever `config/image.php` says; nothing reads that file any more |
| `bootstrap/providers.php` | `OmnibusServiceProvider` added | The Omnibus package does not load |
| `config/concord.php` | `Webkul\Omnibus\Providers\ModuleServiceProvider` added | The Omnibus model is not registered |
| `config/cache.php` | `default` moved from `file` to `database`; `storage` and `failover` stores added; `serializable_classes` set | Nothing — your `.env` value still wins. See the note below before copying Laravel 13's own version of this file |
| `config/filesystems.php` | `r2` disk added, `report` keys added, `serve` commented out on the `private` disk | The Cloudflare R2 driver has no disk to configure, and the `private` disk keeps serving its files through temporary URLs |
| `config/logging.php`, `config/mail.php`, `config/services.php`, `config/queue.php`, `config/session.php`, `config/database.php`, `config/sanctum.php` | The Laravel 13 renames and additions listed above | Nothing — the old names still resolve, apart from the SMTP encryption noted above |
| `config/broadcasting.php` | Replaced with Laravel 13's own version: `default` reads `BROADCAST_CONNECTION` (it read the pre-Laravel 11 `BROADCAST_DRIVER`, which `.env.example` never mentioned, so broadcasting could not be switched on at all), and `reverb` and `ably` connections were added | Broadcasting stays off however `.env` is set. If you had worked around this by setting `BROADCAST_DRIVER`, rename it to `BROADCAST_CONNECTION` |
| `config/imagecache.php` | `cache_driver` removed | Nothing — the key is simply unread |
| `config/purify.php` | Serializer store default follows the new cache default | Nothing — your `.env` value still wins |
| `config/repository.php` | Unchanged since v2.4.12; earlier 2.4 releases differ in comments only | Nothing. Do not bother replacing it |

#### `config/responsecache.php` key map

```diff
- 'cache_store'               => ...    + 'cache' => ['store'               => ...]
- 'cache_lifetime_in_seconds' => ...    + 'cache' => ['lifetime_in_seconds' => ...]
- 'cache_tag'                 => ...    + 'cache' => ['tag'                 => ...]
- 'cache_bypass_header.name'  => ...    + 'bypass' => ['header_name'        => ...]
- 'cache_bypass_header.value' => ...    + 'bypass' => ['header_value'       => ...]
- 'add_cache_time_header'     => ...    + 'debug'  => ['enabled'            => ...]
- 'cache_time_header_name'    => ...    + 'debug'  => ['cache_time_header_name' => ...]
- 'cache_age_header_name'     => ...    + 'debug'  => ['cache_age_header_name'  => ...]
- 'add_cache_age_header'      => ...    (removed — `debug.enabled` now gates every debug header)
- 'serializer' => DefaultSerializer::class
+ 'serializer' => JsonSerializer::class
```

v8 also adds `debug.cache_status_header_name`, `debug.cache_key_header_name` and an `ignored_query_parameters` list that keeps UTM, `gclid` and `fbclid` parameters from splitting the cache. Take the file from this release rather than patching yours key by key.

#### A note on `config/cache.php`

Bagisto sets `serializable_classes` to `true`. Laravel 13's own skeleton ships it as `false`, which blocks every object during unserialization. Bagisto's repository layer — the `CacheableRepository` trait on `Webkul\Core\Eloquent\Repository`, which roughly a hundred repositories extend — caches Eloquent models and collections, and `false` would hand them back as `__PHP_Incomplete_Class`. Omitting the key entirely behaves as it always did, so an untouched v2.4 `config/cache.php` is safe; the danger is copying Laravel 13's version in wholesale.

The shipped default for `CACHE_STORE` also moved from `file` to `database`, and a new root migration creates the `cache` and `cache_locks` tables `php artisan migrate` needs for it. An existing `.env` almost certainly pins `CACHE_STORE` already, in which case nothing changes.

#### Environment file

`.env.example` gained `AWS_URL`, `AWS_ENDPOINT` and the seven `R2_*` variables for the new storage drivers. None of them are required — the disks are configurable from the admin instead — and no variable was removed or renamed, so an existing `.env` needs no edit to keep working.

It also gained `BROADCAST_CONNECTION` and the `REVERB_*` and `PUSHER_*` blocks those connections read. `BROADCAST_CONNECTION` ships as `null`, which leaves broadcasting off and keeps the admin panel from loading any websocket client; setting it to `reverb` or `pusher` is what turns the live order notifications in the admin notification bell on.

**Enable it together with a queue worker.** `QUEUE_CONNECTION` ships as `sync`, which broadcasts inline inside the request that placed the order. `Webkul\Notification\Listeners\Order` logs a broadcaster failure instead of letting it propagate, so an unreachable websocket server cannot fail a checkout — but a server that hangs rather than refusing the connection still holds the shopper's request open. In production point `QUEUE_CONNECTION` at `database` or `redis` and run a worker for the queue the notification events declare:

```bash
php artisan queue:work --queue=broadcastable
```

---

### Relocated Configuration Codes

**Impact Probability: Medium**

Admin → Configuration was reorganised, and two settings moved to the page they belong to. A migration carries the stored values across, so nothing is lost and no manual database work is needed:

| Before | After |
|---|---|
| `catalog.products.storefront.buy_now_button_display` | `catalog.products.product_view_page.buy_now_button_display` |
| `sales.checkout.my_cart.summary` | `sales.checkout.mini_cart.summary` |

Custom code that reads either code is not migrated for you:

```diff
- core()->getConfigData('sales.checkout.my_cart.summary')
+ core()->getConfigData('sales.checkout.mini_cart.summary')
```

The search settings moved too, and are covered in the search section below.

---

### Storefront Breadcrumbs Moved Into the Shop Package

**Impact Probability: Medium**

The storefront breadcrumb trails no longer live in the application. `routes/breadcrumbs.php` was deleted and its trails now ship inside the package that owns them, at `packages/Webkul/Shop/src/Routes/breadcrumbs.php`, registered by `ShopServiceProvider`. Every trail keeps its name, so a view calling `<x-shop::breadcrumbs name="orders" />` needs no change.

**Delete your `routes/breadcrumbs.php`.** `config/breadcrumbs.php` still points `files` at it, and the package loads its own trails regardless — so a file left in place registers the same names twice and `Diglactic\Breadcrumbs\Manager::for()` throws `DuplicateBreadcrumbException` on the storefront pages that render a breadcrumb:

```
Diglactic\Breadcrumbs\Exceptions\DuplicateBreadcrumbException: Breadcrumb name "home" has already been registered
```

That `files` key is deliberately left alone, because it stays useful as an extension point: a `routes/breadcrumbs.php` holding only your **own** trail names still loads and is the supported way to add breadcrumbs without touching the package. To change a trail Bagisto already defines, override it in a package of your own rather than redefining the name.

---

### Storage Directories Renamed

**Impact Probability: Medium**

The directories under `storage/app/public` and `storage/app/private` are now plural, kebab-case names, and a record's files sit under the record rather than in a directory of their own:

| Before | After |
|---|---|
| `product/{id}` | `products/{id}` |
| `category/{id}` | `categories/{id}` |
| `channel/{id}` | `channels/{id}` |
| `review/{id}` | `reviews/{id}` |
| `attribute_option` | `attribute-options` |
| `configuration` | `configurations` |
| `product_downloadable_links/{productId}` | `products/{productId}/downloadable-links` and `downloadable-samples` |
| `rma/{id}`, `rma-conversation/{messageId}` | `rmas/{id}/images` and `rmas/{id}/conversations/{messageId}` |

A migration moves the files and rewrites every stored path — product images and videos, attribute values, category logos and banners, channel logos and favicons, review attachments, swatches, configuration uploads, downloadable files and RMA attachments. A row whose file has already gone is left pointing at the new location, and a directory that still holds unreferenced files is kept rather than deleted.

Custom code that builds one of these paths itself is not migrated for you:

```diff
- $file->store('product/'.$product->id)
+ $file->store('products/'.$product->id)
```

Code that decides behaviour from a path string needs the same attention, and a grep for the old directory name will not find it. Bagisto's own image filters matched `'/category'` to pick category dimensions, which stopped matching `categories` and silently fell back to the slider size.

---

### Route Names Are snake_case

**Impact Probability: Medium**

Every hyphenated route name is now snake_case. URLs are unchanged — only the name passed to `route()`:

```diff
- route('admin.sales.rma.requests.send-message')
+ route('admin.sales.rma.requests.send_message')
```

Fifty-six names changed, among them `admin.sales.rma.custom-fields.*`, `admin.configuration.search-engines.test-connection`, `shop.customers.account.gdpr.pdf-view`, `customer.social-login.callback` and `paypal.smart-button.create-order`. A module that names a route as a string — in a controller, a Blade view, an ACL entry or a menu — has to use the new spelling.

---

### Translation Keys Are kebab-case

**Impact Probability: Medium**

Every remaining underscored translation key is now kebab-case, across all 22 locales:

```diff
- trans('admin::app.eu_withdrawal.view.received_at')
+ trans('admin::app.eu-withdrawal.view.received-at')
```

A theme or module that overrides one of these keys, or reads it with `trans()`, must be updated. Note the deliberate split from the route names above: the same feature is `admin.sales.eu_withdrawals.index` as a route and `admin::app.eu-withdrawal.…` as a translation key.

Keys whose segment is data rather than a name keep their own casing — currency codes such as `seeders.core.currencies.AED` and locale codes such as `pt_BR`.

---

### The Phone Field Is Named Phone Everywhere

**Impact Probability: Low**

The one customer phone number was labelled three different ways. Every key that names it is now `phone`, in all 22 locales, and the label reads as the locale's own word for it:

```diff
- trans('shop::app.checkout.onepage.address.telephone')
- trans('admin::app.customers.customers.index.create.contact-number')
- trans('shop::app.customers.account.orders.invoice-pdf.contact')
+ trans('shop::app.checkout.onepage.address.phone')
+ trans('admin::app.customers.customers.index.create.phone')
+ trans('shop::app.customers.account.orders.invoice-pdf.phone')
```

The unused `shop::app.customers.account.orders.invoice-pdf.contact-number` is gone. Keys that name a different field keep their own wording — an inventory source's `contact-number`, the shipping origin's, and the contact form's `phone-number`.

---

### Blade View Paths Are kebab-case

**Impact Probability: Low**

`shop::customers.account.downloadable_products` was the last view path carrying an underscore, and is now `downloadable-products`. A theme that publishes or overrides that view has to move it to the new path:

```diff
- resources/themes/<theme>/views/customers/account/downloadable_products/index.blade.php
+ resources/themes/<theme>/views/customers/account/downloadable-products/index.blade.php
```

The route name stays `shop.customers.account.downloadable_products.index`, as route names are snake_case, and the `view_render_event` names the view fires are unchanged so existing listeners keep working.

---

### Theme Section Media Are Stored As Bare Paths

**Impact Probability: Medium**

Theme sections used to record an upload as `storage/themes/...` and a link as a whole URL on the store's domain. Neither survived a change of domain or a move to a remote disk. Sections now store the bare path — `themes/...` — and resolve it when the page is rendered.

**Nothing converts what is already stored, and nothing needs to.** Every spelling a section has ever recorded is understood when it is read, so a store upgrades without a data migration and without a maintenance window for one:

| Stored before | Read as |
|---|---|
| `storage/themes/...` | the path `themes/...` |
| A whole URL on this store's own domain | a link resolved against the site serving the request |
| `src="storage/..."` or `src="/storage/..."` in authored markup | the URL the upload is served from |

Re-saving a section in Appearance rewrites it to the current spelling. Nothing obliges you to, and a store that never touches its sections keeps working.

#### If you override a section view

A view that built its own URLs from the stored value will now build them from a string that no longer contains `storage`. The storefront carousel is the clearest case:

```diff
- <img src="{{ $image['image'] }}"
-      srcset="{{ str_replace('storage', 'cache/large', $image['image']) }} 1280w, ...">
+ @php $resolved = bagisto_theme_storage()->imageUrls($image['image'] ?? null); @endphp
+ <img src="{{ $resolved['url'] }}"
+      srcset="{{ $resolved['srcset']['large'] }} 1280w, ...">
```

`bagisto_theme_storage()` returns `Webkul\Theme\ThemeStorage`, which resolves a stored path against whichever disk the store is on:

| Method | Returns |
|---|---|
| `url($path)` | The URL the original is served from |
| `resizedUrl($path, $size)` | The URL of one resized copy — `small`, `medium` or `large` |
| `imageUrls($path)` | `['url' => ..., 'srcset' => ['small' => ..., 'medium' => ..., 'large' => ...]]` |
| `mediaReference($path)` | The reference to write into authored markup, resolved as the page renders |
| `resolveMarkup($markup)` | Authored markup with every reference in it resolved to the URL it is served from |
| `embedUrl($path)` | A stored path as a root-relative URL on a local disk |
| `normalize($path)` | The stored value reduced to a disk path, tolerating an old `storage/` prefix |

`bagisto_asset()` is unchanged and still resolves what a theme *ships*; `bagisto_theme_storage()` resolves what it *stores*.

#### Uploads inside authored markup are references

A static content section used to have the upload's URL written into its HTML when the file was added, which froze the store's directory into the markup. An install served from a subdirectory — `example.com/shop/public` — then asked for `/storage/...` at the domain root and got nothing. The editor now writes a reference instead, and it is resolved against the site serving the page each time the section renders:

```html
<img src="__media__/themes/default/sections/8/hero.webp" alt="">
```

**A theme that renders section markup itself must resolve it.** Nothing else changes for a theme that uses the core view.

```diff
- {!! $data['html'] !!}
+ {!! bagisto_theme_storage()->resolveMarkup($data['html']) !!}
```

Do the same for `$data['css']`, which may carry a reference inside `url(...)`.

**There is no migration, and none is needed.** `resolveMarkup()` also resolves the `/storage/...` form written before this release, so markup already in the database keeps working and gains the same directory independence. Re-saving a section in Appearance rewrites it to a reference; nothing obliges you to.

The upload endpoint changed with it. `POST admin/appearance/sections/{id}/media` now answers with `ref` where it used to answer with `url`, so a custom editor calling it reads the new key:

```diff
- this.write(`<img src="${response.data.url}" alt="" />`);
+ this.write(`<img src="${response.data.ref}" alt="" />`);
```

`path` is unchanged, and an image field still records the bare path rather than a reference — only markup authored by hand carries one.

#### Seeded section media was filed under the wrong section

A section's uploads live under `themes/{theme}/sections/{id}`, and both the delete observer and the routine that clears unreferenced files act on that directory. The demo content the installer seeds did not follow it: the markup of the section with id 5 pointed at `sections/8`, id 6 at `sections/10`, id 8 at `sections/12` and id 10 at `sections/4`. Only the image carousel lined up.

**On a store seeded before this release, deleting one of those sections deletes another one's images.** Removing the section with id 8 clears `sections/8`, which is where the section with id 5 keeps its pictures. Editing a section has the same effect, because the unreferenced files are cleared as it is saved.

The seeder now files each upload under the section that owns it, so a **fresh install is unaffected**. Anything uploaded through Appearance was always filed correctly — this is seeded demo content only.

To repair a store that already has it, move each file to the section that references it and repoint the reference. Run this once, with the store in maintenance mode:

```php
// php artisan tinker
use Illuminate\Support\Facades\Storage;
use Webkul\Theme\Models\Section;

foreach (Section::with('translations')->get() as $section) {
    $home = 'themes/'.$section->theme_code.'/sections/'.$section->id;

    foreach ($section->translations as $translation) {
        foreach (['options', 'draft_options'] as $column) {
            if (! is_array($translation->{$column})) {
                continue;
            }

            $encoded = json_encode($translation->{$column});

            preg_match_all('#sections\\\\/(\d+)\\\\/([^"\\\\]+)#', $encoded, $matches, PREG_SET_ORDER);

            foreach ($matches as [$whole, $directory, $file]) {
                if ((int) $directory === (int) $section->id) {
                    continue;
                }

                $from = 'themes/'.$section->theme_code.'/sections/'.$directory.'/'.$file;

                if (Storage::exists($from) && ! Storage::exists($home.'/'.$file)) {
                    Storage::move($from, $home.'/'.$file);
                }

                $encoded = str_replace('sections\\/'.$directory.'\\/'.$file, 'sections\\/'.$section->id.'\\/'.$file, $encoded);
            }

            $translation->{$column} = json_decode($encoded, true);
        }

        $translation->save();
    }
}
```

It moves the files rather than the directories, because the directory a section should move into is often one another section is already using. Run it before deleting or editing any of the seeded sections. The directories it empties can then be removed:

```php
foreach (Storage::directories('themes') as $theme) {
    foreach (Storage::directories($theme.'/sections') as $directory) {
        if (empty(Storage::files($directory))) {
            Storage::deleteDirectory($directory);
        }
    }
}
```

---

### Image Processing Moved to Laravel's Image Component

**Impact Probability: Medium**

Laravel 13 ships its own image component, so Bagisto no longer maintains a wrapper around Intervention Image. Code that resizes or re-encodes images now uses `Illuminate\Image`.

> [!NOTE]
> Intervention Image has **not** gone away — Laravel's `gd` and `imagick` drivers are built on it, so it remains a dependency and is now required at `^4.2`. What changed is that Bagisto no longer writes against its API.

#### Removed Image Classes

These were part of `webkul/imagecache` and were unreachable from the application — nothing resolved or instantiated them. They modelled Intervention's API (`brightness()`, `gamma()`, `colorize()`, `pixelate()`, `pad()`), which Laravel's component does not provide, so they were removed rather than rewritten:

| Removed | Replacement |
|---------|-------------|
| `Webkul\ImageCache\ImageCache` | None. Use `Illuminate\Image\ImageManager` (`image_manager()` or the `Image` facade). |
| `Webkul\ImageCache\CachedImage` | `Illuminate\Image\Image` |
| `Webkul\ImageCache\HashableClosure` | None. |

If your package referenced any of them, move to Laravel's component.

#### API Changes

`image_manager()` still exists, but now returns `Illuminate\Image\ImageManager` rather than Intervention's:

```diff
- image_manager()->read($uploadedFile)->encodeByExtension('webp');
+ image_manager()->fromUpload($uploadedFile)->toWebp()->toBytes();

- image_manager()->read($path)->encodeByMediaType();
+ image_manager()->fromPath($path)->toBytes();
```

Reading is now explicit about its source — `fromPath()`, `fromUpload()`, `fromBytes()`, `fromUrl()`, `fromStorage()`, `fromStream()`, `fromBase64()` — instead of one `read()` that guessed. Encoding is `toWebp()`, `toJpeg()`, `toPng()` and so on, followed by `toBytes()`.

Cache filters and templates type-hint the new image class:

```diff
- use Intervention\Image\Interfaces\ImageInterface;
+ use Illuminate\Image\Image;

- public function applyFilter(ImageInterface $image): ImageInterface
+ public function applyFilter(Image $image): Image
```

`cover()`, `contain()`, `crop()`, `resize()`, `scale()`, `rotate()`, `blur()`, `sharpen()`, `grayscale()` and the flips carry the same names, so filter bodies usually need no change.

The same change applies to the image cache templates a theme registers under `customize.image_cache.templates` in `config/themes.php`. A template, there or in `config/imagecache.php`, has to be a class with a public `applyFilter()` method; a closure is no longer applied.

#### Configuration

The driver is now read from `config/images.php`, under a new key:

```diff
- // config/image.php
- 'driver' => 'gd',

+ // config/images.php
+ 'default' => env('IMAGE_DRIVER', 'gd'),
```

Delete `config/image.php` and add `config/images.php`. Supported drivers are still `gd` and `imagick`.

`config/imagecache.php` stays, minus its `cache_driver` key, which nothing read.

#### Composer

```diff
- "intervention/image": "^2.4|^3.0",
+ "intervention/image": "^4.2",
```

The `dont-discover` entry for `intervention/image` can go — v4 ships no Laravel service provider to discover.

---

### Remote Storage Drivers

**Impact Probability: Medium**

Amazon S3 and Cloudflare R2 can now be chosen at `Configuration → File Management`. The local disk remains the default and an upgraded store keeps using it, so this is opt-in.

Three things are worth knowing before you opt in:

- The chosen driver is applied at boot by `Webkul\Core\Filesystem\StorageConfigurator`, which sets `filesystems.default`. **Once a driver is recorded, it wins over `FILESYSTEM_DISK`.** With nothing recorded — the state every upgraded store is in — the environment is left alone.
- Nothing copies existing files. Switching the disk changes where *new* uploads go; the media already under `storage/app/public` has to be moved across yourself, or the store will serve broken images.
- Sized images keep going through the `cache/{template}/{path}` route on S3 and R2, which reads the file through the disk. That covers product images, category logos and banners, swatches, and every theme template `image_urls()` builds, where 2.4 linked each size to the stored file on a remote disk.

If you maintain your own `config/filesystems.php`, add the `r2` disk from this release. `league/flysystem-aws-s3-v3` arrives with `composer install` and backs both drivers; the enum that describes them refuses a driver whose adapter is missing rather than failing at upload time.

---

### The Omnibus Package

**Impact Probability: Low**

A new first-party package, `Webkul\Omnibus`, records per-channel lowest-price snapshots for EU Omnibus Directive compliance. It is **off by default** (`catalog.products.omnibus.is_enabled`, falling back to `OMNIBUS_ENABLED`), so an upgraded store behaves exactly as before until it is switched on.

Being a new package, it needs the three registrations any Bagisto package needs. If you have not customised these files, taking them from the release is enough:

```diff
  // composer.json
+ "Webkul\\Omnibus\\": "packages/Webkul/Omnibus/src",

  // bootstrap/providers.php
+ OmnibusServiceProvider::class,

  // config/concord.php
+ Webkul\Omnibus\Providers\ModuleServiceProvider::class,
```

Run `composer dump-autoload` if you edited `composer.json` by hand. `php artisan migrate` creates the `product_omnibus_prices` table.

Once enabled, the package leans on the scheduler — `omnibus:snapshot-prices` every fifteen minutes and `omnibus:purge-old-snapshots` daily — so `php artisan schedule:run` has to be running on cron. History accumulates from the first snapshot onward, so the storefront shows a 30-day low only once thirty days of snapshots exist.

---

### PostgreSQL Support

**Impact Probability: Medium**

Bagisto now runs on PostgreSQL 16 alongside MySQL 8.0 and MariaDB 10.11, and CI covers all three. Existing MySQL and MariaDB installations are unaffected and need no action.

> [!NOTE]
> Three dozen migrations changed in this release for PostgreSQL's sake — `json` columns declared as `jsonb`, `boolean` flags that hold more than two values declared as `tinyInteger`, an index check that reads its prefix from `DB`, a nullable `category_translations.url_path`, and a `CONCAT()` rewritten through `db_grammar()`. Every one of them edits a migration that already ran on your store, so `php artisan migrate` will not replay them and there is nothing to do. They shape fresh installs only.

Custom code, however, has to survive on either engine. Two rules cover most of it:

```diff
// LIKE is case-insensitive on MySQL and case-sensitive on PostgreSQL
- ->where('name', 'like', "%{$term}%")
+ ->where('name', db_grammar()->caseInsensitiveLike(), "%{$term}%")
```

```diff
// MySQL coerces "" to 0/NULL; PostgreSQL rejects it outright
+ public function setSpecialPriceAttribute($value)
+ {
+     $this->attributes['special_price'] = $value === '' ? null : $value;
+ }
```

Beyond those: every non-aggregated column in a `SELECT` must appear in `GROUP BY`, `CAST(... AS CHAR)` truncates to one character so use `VARCHAR(255)`, and `DB::raw()` inside `updateOrCreate()` fails on insert. Boolean columns need a `'boolean'` cast to come back consistently from both drivers.

The production Docker images ship in all three flavours — see [`docker/production/README.md`](docker/production/README.md).

---

### Search Architecture Refactored to Engine-Agnostic Design

**Impact Probability: High**

Bagisto v2.5 replaces the tightly-coupled Elasticsearch search infrastructure in the `Product` package with an engine-agnostic design using the Strategy and Manager patterns. Another engine (e.g., Algolia, Pinecone) plugs in behind the same contracts, needing only a case in the core `SearchEngineEnum` — see [Adding a Custom Search Engine](#adding-a-custom-search-engine).

> [!IMPORTANT]
> If your store searches with Elasticsearch and its credentials live only in `.env`, read [Elasticsearch connection settings are now recorded in the admin](#elasticsearch-connection-settings-are-now-recorded-in-the-admin) below before you upgrade. This is the one part of the refactor that can take a working store offline.

#### Removed Search Classes

The following classes have been **deleted**:

| Removed | Replacement |
|---------|-------------|
| `Webkul\Product\Repositories\ElasticSearchRepository` | `Webkul\Product\Services\Search\Engines\ElasticSearchEngine` |
| `Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex` | `Webkul\Product\Jobs\Search\IndexProducts` |
| `Webkul\Product\Jobs\ElasticSearch\DeleteIndex` | `Webkul\Product\Jobs\Search\DeleteProducts` |
| `Webkul\Product\Helpers\Product` | See "Renamed Methods" below |

If your custom code imports any of these classes, update the imports to their replacements.

#### Removed Methods

| Removed | Replacement |
|---------|-------------|
| `ProductRepository::setSearchEngine(string)` | `ProductRepository::setSearchContext(SearchContextEnum)` |

#### Renamed Methods

| Old | New |
|-----|-----|
| `Product::formatElasticSearchIndexName(channel, locale)` | `ElasticSearchEngine::formatIndexName(channel, locale)` |

The `Webkul\Product\Helpers\Product` class has been deleted. Its only method moved to `ElasticSearchEngine`, unchanged, so index names are the same and no reindex is required for the rename alone:

```diff
- use Webkul\Product\Helpers\Product;
- Product::formatElasticSearchIndexName($channelCode, $localeCode);
+ use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;
+ ElasticSearchEngine::formatIndexName($channelCode, $localeCode);
```

#### New Enums

Four enums in `Webkul\Product\Enums` replace the hardcoded search-related strings:

**`SearchEngineEnum`** — search driver values:
- `SearchEngineEnum::DATABASE` (`'database'`)
- `SearchEngineEnum::ELASTIC` (`'elastic'`)

**`SearchContextEnum`** — search context values:
- `SearchContextEnum::STOREFRONT` (`'storefront'`)
- `SearchContextEnum::ADMIN` (`'admin'`)

**`SearchEngineStatusEnum`** — the verdict of a connection probe: `available`, `unreachable`, `unauthorized`, `incompatible`, `misconfigured`.

**`ElasticAuthEnum`** — how the store authenticates to Elasticsearch: `none`, `basic`, `api_key`, `cloud_api_key`, `cloud_basic`.

#### New Contracts

Three contracts define the engine abstraction:

- `Webkul\Product\Contracts\SearchEngine` — search operations (`search`, `getSuggestions`, `getMaxPrice`, `findBySlug`)
- `Webkul\Product\Contracts\SearchIndexer` — indexing operations (`indexBatch`, `deleteBatch`, `reindexFull`)
- `Webkul\Product\Contracts\SearchEngineConnection` — optional, for an engine reached over a network (`configure`, `probe`, `describesRecorded`). An engine that needs nothing to reach, such as the database, simply does not implement it.

#### New Services

| Class | Purpose |
|-------|---------|
| `Services\Search\SearchEngineManager` | Centralizes all config resolution — single source of truth for which engine/driver is active |
| `Services\Search\Engines\DatabaseEngine` | `SearchEngine` implementation for database mode |
| `Services\Search\Engines\ElasticSearchEngine` | `SearchEngine` implementation for Elasticsearch |
| `Services\Search\Indexers\ElasticSearchIndexer` | `SearchIndexer` implementation wrapping the existing `Helpers\Indexers\ElasticSearch` |
| `Services\Search\Indexers\NullIndexer` | No-op `SearchIndexer` for database mode (eliminates config guards) |
| `Services\Search\Connections\ElasticConnection` | `SearchEngineConnection` for Elasticsearch — applies the admin settings and probes the cluster |
| `Services\Search\SearchEngineAvailability` | Probes an engine and caches the verdict, keyed per engine |
| `Services\Search\SearchEngineConfigurator` | Applies every connectable engine's recorded settings at boot |
| `Services\Search\SearchEngineOptions` | The shared option list behind the three engine selects |

#### Search Migration Steps

1. **Update search engine config checks:**

   All scattered `core()->getConfigData('catalog.products.search.engine')` checks are now centralized in `SearchEngineManager`. If your custom code checks the search engine config directly, use the manager instead:

   ```diff
   - if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
   -     $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
   - }
   - $this->productRepository->setSearchEngine($searchEngine ?? 'database');
   + use Webkul\Product\Enums\SearchContextEnum;
   + $this->productRepository->setSearchContext(SearchContextEnum::STOREFRONT);
   ```

   For admin context:

   ```diff
   - if (
   -     core()->getConfigData('catalog.products.search.engine') == 'elastic'
   -     && core()->getConfigData('catalog.products.search.admin_mode') == 'elastic'
   - ) {
   -     $searchEngine = 'elastic';
   - }
   - $this->productRepository->setSearchEngine($searchEngine ?? 'database');
   + use Webkul\Product\Enums\SearchContextEnum;
   + $this->productRepository->setSearchContext(SearchContextEnum::ADMIN);
   ```

2. **Update job dispatches:**

   ```diff
   - use Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex;
   - use Webkul\Product\Jobs\ElasticSearch\DeleteIndex;
   + use Webkul\Product\Jobs\Search\IndexProducts;
   + use Webkul\Product\Jobs\Search\DeleteProducts;

   - UpdateCreateIndex::dispatch($productIds);
   + IndexProducts::dispatch($productIds);

   - DeleteIndex::dispatch($productIds);
   + DeleteProducts::dispatch($productIds);
   ```

   The new jobs use `SearchEngineManager` internally. When the master engine is `database`, the `NullIndexer` handles the call as a no-op — no config guards needed in your code.

3. **Update `ElasticSearchRepository` usage:**

   ```diff
   - use Webkul\Product\Repositories\ElasticSearchRepository;
   + use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;
   ```

   If you were calling `ElasticSearchRepository` methods directly, resolve the engine through the manager instead of naming the class, so the store's own configuration decides which one you get:

   ```diff
   - $results = $this->elasticSearchRepository->search(...)
   + $engine = app(SearchEngineManager::class)->engine(SearchContextEnum::STOREFRONT);
   + $results = $engine->search($params, $options);
   ```

4. **Update Artisan indexer commands:**

   The `indexer:index` command now uses `search` instead of `elastic` as the type flag:

   ```diff
   - php artisan indexer:index --type=elastic --mode=full
   + php artisan indexer:index --type=search --mode=full
   ```

5. **Update `Product::formatElasticSearchIndexName()` calls:**

   ```diff
   - use Webkul\Product\Helpers\Product;
   - $index = Product::formatElasticSearchIndexName($channelCode, $localeCode);
   + use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;
   + $index = ElasticSearchEngine::formatIndexName($channelCode, $localeCode);
   ```

6. **Update the config check in a custom DataGrid:**

   A DataGrid that decides for itself whether to search with Elasticsearch should ask the manager rather than reading the config codes, which have moved:

   ```diff
   - if (
   -     core()->getConfigData('catalog.products.search.engine') != 'elastic'
   -     || core()->getConfigData('catalog.products.search.admin_mode') != 'elastic'
   - ) {
   + use Webkul\Product\Enums\SearchContextEnum;
   + use Webkul\Product\Enums\SearchEngineEnum;
   + use Webkul\Product\Services\Search\SearchEngineManager;
   +
   + $manager = app(SearchEngineManager::class);
   +
   + if ($manager->resolveDriver(SearchContextEnum::ADMIN) === SearchEngineEnum::DATABASE) {
         parent::processRequest();
         return;
     }
   ```

   The query itself is unchanged. `Webkul\Core\Facades\ElasticSearch` is still the supported way to issue a raw Elasticsearch query, and the core `ProductDataGrid` still uses it — only the decision of *whether* to reach for it moved behind the manager.

7. **Reading configuration through the manager:**

   ```php
   use Webkul\Product\Enums\SearchContextEnum;
   use Webkul\Product\Enums\SearchEngineEnum;
   use Webkul\Product\Services\Search\SearchEngineManager;

   $manager = app(SearchEngineManager::class);

   if ($manager->isExternalEngineEnabled()) {
       // An engine other than the database is switched on
   }

   if ($manager->resolveDriver(SearchContextEnum::STOREFRONT) === SearchEngineEnum::ELASTIC) {
       // This context searches with Elasticsearch
   }
   ```

   `engine(SearchContextEnum)`, `indexer()` and `getMasterEngine()` round out the API.

8. **Update stored configuration codes:**

   Search settings have moved out of Catalog into a **Search Engines** section of their own, at `Configuration → Search Engines`. The stored `core_config` codes move with them, and a migration carries existing values across:

   | Before | After |
   |---|---|
   | `catalog.products.search.engine` | `search_engines.general.settings.engine` |
   | `catalog.products.search.admin_mode` | `search_engines.general.products.admin_mode` |
   | `catalog.products.search.storefront_mode` | `search_engines.general.products.storefront_mode` |
   | `catalog.products.search.min_query_length` | `search_engines.elastic.settings.min_query_length` |
   | `catalog.products.search.max_query_length` | `search_engines.elastic.settings.max_query_length` |

   A new `search_engines.general.settings.enabled` switch now gates every external engine — when it is off, both contexts search the database whatever the modes say. The migration turns it on for any store that already had a non-database engine selected, so an upgrade keeps searching the way it did before.

   The two context modes also accept an empty value meaning *use the default engine*, so a store no longer has to repeat its engine choice in three places.

   Read them through `SearchEngineManager` rather than by code, as in step 7 — its `ENABLED_KEY`, `ENGINE_KEY`, `ADMIN_MODE_KEY` and `STOREFRONT_MODE_KEY` constants are the supported reference.

#### Elasticsearch connection settings are now recorded in the admin

Host, credentials, authentication type and index prefix can be set at `Configuration → Search Engines → Elasticsearch`, and `SearchEngineConfigurator` applies them over `config/elasticsearch.php` at boot.

> [!WARNING]
> This is not purely additive, and it is the one step an Elasticsearch store must act on.
>
> `ElasticConnection::configure()` returns early only while the store has recorded **no** Elasticsearch setting at all. The migration in step 8 records `search_engines.elastic.settings.min_query_length` and `max_query_length` for any store that had saved the old catalog search page — which is every store that turned Elasticsearch on from the admin. From then on, `configure()` runs in full: it sets `elasticsearch.connection` from the recorded authentication type, and **nulls out the credentials that type does not read**.
>
> With the credential fields still empty after the upgrade, the authentication type resolves to *none*, which means the `default` connection with `user` and `pass` cleared. A store whose `ELASTICSEARCH_CONNECTION` was `api` or `cloud`, or whose cluster is behind `ELASTICSEARCH_USER` / `ELASTICSEARCH_PASS`, will stop being able to reach it.
>
> **After migrating, open `Configuration → Search Engines → Elasticsearch`, set the authentication type, re-enter the host and credentials, and use Test Connection.** The `ELASTICSEARCH_*` variables remain the fallback for anything you leave blank, so a cluster reached anonymously over the default connection needs nothing.

Changing the index prefix here has the same effect as changing `ELASTICSEARCH_INDEX_PREFIX`: it renames the indices the store reads, so reindex with `php artisan indexer:index --type=search --mode=full` afterwards.

#### Adding a Custom Search Engine

To add a new search engine (e.g., Algolia):

1. Implement `Webkul\Product\Contracts\SearchEngine`:

   ```php
   class AlgoliaEngine implements SearchEngine
   {
       public function search(array $params, array $options): array { /* ... */ }
       public function getSuggestions(?string $query): ?string { /* ... */ }
       public function getMaxPrice(array $params = []): float { /* ... */ }
       public function findBySlug(string $slug): ?int { /* ... */ }
   }
   ```

2. Implement `Webkul\Product\Contracts\SearchIndexer`:

   ```php
   class AlgoliaIndexer implements SearchIndexer
   {
       public function indexBatch(array $products): void { /* ... */ }
       public function deleteBatch(array $productIds): void { /* ... */ }
       public function reindexFull(): void { /* ... */ }
   }
   ```

3. Add a case to `SearchEngineEnum`. PHP enums cannot be extended, so this is the one change to core code — the manager resolves the stored engine through `SearchEngineEnum::tryFrom()`, and an unknown value falls back to the database:

   ```php
   case ALGOLIA = 'algolia';
   ```

4. Register named bindings in your service provider:

   ```php
   $this->app->singleton('product.search.engine.algolia', AlgoliaEngine::class);
   $this->app->singleton('product.search.indexer.algolia', AlgoliaIndexer::class);
   ```

   The `SearchEngineManager` resolves your engine once the stored engine matches the enum case and `search_engines.general.settings.enabled` is on.

5. If your engine is reached over a network, implement `Webkul\Product\Contracts\SearchEngineConnection` and bind it as well. This is what gives it settings in the admin and a working Test Connection button; an engine without one is treated as always available.

   ```php
   class AlgoliaConnection implements SearchEngineConnection
   {
       public function configure(array $overrides = []): void { /* Push stored settings into config() */ }
       public function probe(array $overrides = []): array { /* Return ['status' => SearchEngineStatusEnum::AVAILABLE->value, ...] */ }
       public function describesRecorded(array $overrides): bool { /* Do these values match what is stored? */ }
   }
   ```

   ```php
   $this->app->singleton('product.search.connection.algolia', AlgoliaConnection::class);
   ```

   `configure()` runs at boot for every bound connection, so keep it cheap and read `core_config` directly rather than through a repository — the table may not exist yet on a fresh install. Return early when nothing is recorded, so the environment file stays in charge of an unconfigured store. `probe()` must return a `status` drawn from `SearchEngineStatusEnum`; anything else it returns is passed through to the admin as detail. `describesRecorded()` lets the Test Connection button tell a trial of unsaved values from a test of the saved ones — both `configure()` and `probe()` take the same `$overrides` array for that purpose.

6. Add a configuration group keyed by the enum value, so the settings land on their own page:

   ```php
   ['key' => 'search_engines.algolia', 'name' => '...engines.algolia', 'icon' => '...', 'sort' => 3],
   ['key' => 'search_engines.algolia.settings', 'fields' => [/* App id, api key, ... */]],
   ```

   > [!IMPORTANT]
   > Keep the `settings` segment. `SystemConfig::getDefaultConfig()` strips the **first** segment of a
   > config key and looks the rest up in `config()`, so `search_engines.algolia.<field>` would resolve
   > against a `config/algolia.php` if one existed and silently override your declared default.

7. Add the engine's label at `admin::app.configuration.index.search-engines.engines.algolia` in all 22 locales. It names the option in every engine select and titles the settings page.

The status strings under `search-engines.test-connection.statuses.*` take an `:engine` placeholder, so they read correctly for your engine without being touched.

---

### Tailwind CSS Upgraded from v3 to v4

**Impact Probability: High**

Bagisto v2.5 upgrades the `Admin`, `Shop`, and `Installer` frontend stacks from Tailwind CSS v3 to v4. v4 is a ground-up rewrite that replaces the PostCSS pipeline with an official Vite plugin, moves configuration from JavaScript to CSS, and renames several utility classes. Any custom theme, extension package, or Blade override that ships its own Tailwind classes will need updates.

Official upgrade reference: https://tailwindcss.com/docs/upgrade-guide.

#### Removed Files

The following files have been **deleted** from each of the three frontend packages:

| Removed | Reason |
|---------|--------|
| `packages/Webkul/Admin/tailwind.config.js` | v4 uses CSS-first configuration (`@theme`, `@utility`, `@custom-variant` in `app.css`). |
| `packages/Webkul/Shop/tailwind.config.js` | Same as above. |
| `packages/Webkul/Installer/tailwind.config.js` | Same as above. |
| `packages/Webkul/Admin/postcss.config.cjs` | `@tailwindcss/vite` replaces the PostCSS pipeline; no PostCSS config is needed. |
| `packages/Webkul/Shop/postcss.config.cjs` | Same as above. |
| `packages/Webkul/Installer/postcss.config.cjs` | Same as above. |

If your custom package copied any of these files, delete them and follow the CSS-first pattern described below.

#### Package Dependency Changes

Each of `Admin`, `Shop`, and `Installer` had their `package.json` updated:

```diff
  "devDependencies": {
-   "autoprefixer": "^10.4.16",
+   "@tailwindcss/vite": "^4.0.0",
-   "postcss": "^8.4.23",
-   "tailwindcss": "^3.3.2",
+   "tailwindcss": "^4.0.0",
    // ...
  }
```

`autoprefixer` and `postcss` are no longer required — the Vite plugin runs Lightning CSS internally and handles vendor prefixing automatically.

If your custom package extended Bagisto's frontend build, mirror the same dependency change and delete your own `postcss.config.*` file.

#### Vite Configuration Changes

Each `vite.config.js` now registers the Tailwind Vite plugin (the Installer's config has no Vue plugin, so it adds only `tailwindcss()`):

```diff
  import { defineConfig, loadEnv } from "vite";
  import vue from "@vitejs/plugin-vue";
  import laravel from "laravel-vite-plugin";
+ import tailwindcss from "@tailwindcss/vite";
  import path from "path";

  export default defineConfig(({ mode }) => {
      // ...
      return {
          // ...
          plugins: [
              vue(),
+             tailwindcss(),
              laravel({ /* ... */ }),
          ],
      };
  });
```

#### `app.css` Configuration Migration

The `@tailwind` directives are replaced with a single `@import`, and every option that previously lived in `tailwind.config.js` (breakpoints, colors, fonts, container, dark mode, content paths) is moved into CSS. Example for `packages/Webkul/Admin/src/Resources/assets/css/app.css`:

```diff
- @tailwind base;
- @tailwind components;
- @tailwind utilities;
+ @import "tailwindcss" source("../../../");
+
+ @custom-variant dark (&:where(.dark, .dark *));
+
+ @theme {
+     --breakpoint-sm: 525px;
+     --breakpoint-md: 768px;
+     --breakpoint-lg: 1024px;
+     --breakpoint-xl: 1240px;
+     --breakpoint-2xl: 1920px;
+
+     --color-darkGreen: #40994A;
+     --color-darkBlue: #0044F2;
+     --color-darkPink: #F85156;
+
+     --font-inter: Inter;
+     --font-icon: icomoon;
+ }
+
+ @utility container {
+     margin-inline: auto;
+     padding-inline: 16px;
+
+     @variant 2xl {
+         max-width: 1920px;
+     }
+ }
```

Every configuration key that lived in v3's `tailwind.config.js` must now be expressed in CSS. The full mapping:

| v3 `tailwind.config.js` | v4 `app.css` |
|-------------------------|--------------|
| `theme.screens.*` | `--breakpoint-*` inside `@theme` |
| `theme.extend.colors.*` | `--color-*` inside `@theme` |
| `theme.extend.fontFamily.*` | `--font-*` inside `@theme` |
| `theme.extend.fontSize.*` | `--text-*` (plus optional `--text-*--line-height`) inside `@theme` |
| `theme.extend.spacing.*` | `--spacing-*` inside `@theme` (or override the base `--spacing` scale) |
| `theme.extend.borderRadius.*` | `--radius-*` inside `@theme` |
| `theme.extend.boxShadow.*` | `--shadow-*` inside `@theme` |
| `theme.extend.zIndex.*` | Removed — use arbitrary `z-<n>` (v4 accepts any integer natively) |
| `theme.container.{center, padding, screens}` | `@utility container { ... @variant <bp> { max-width: ... } }` |
| `darkMode: 'class'` | `@custom-variant dark (&:where(.dark, .dark *));` |
| `content: [...]` | `@import "tailwindcss" source("<relative-path>");` or `@source "..."` |
| `safelist: [{ pattern: /.../ }]` | `@source inline("class-a class-b ...");` |
| `plugins: [require('...')]` | `@plugin "...";` |
| `prefix: 'tw-'` | `@import "tailwindcss" prefix(tw);` |

#### Content and Safelist Path Behavior Changed

In v3, `content: ["./src/Resources/**/*.blade.php"]` explicitly listed every glob. In v4, source detection is automatic but rooted in a **base source directory** you declare, given relative to the `app.css` that declares it:

- `@import "tailwindcss" source("../../../");` — the base Admin and Shop use, which resolves to the package's `src/`. Tailwind then auto-detects `.blade.php`, `.js`, `.vue` and other recognized file types under that path. Rooting at `src/` rather than `src/Resources/` is deliberate: class names written in `Config` and `DataGrids` files — menu, ACL and datagrid icons — are seen without help. The Installer, which has none, roots at `src/Resources/` with `source("../../")`.
- `@source "../../../../../SomePackage/src/Resources/**/*.blade.php";` — adds a specific glob outside the base directory. Count the hops from the directory holding `app.css`, not from the package root.
- `@source inline("icon-a icon-b icon-c");` — the v4 replacement for v3's `safelist` regex, since regex patterns are no longer supported. Enumerate literal class names, or use brace expansion as the storefront does: `@source inline("{icon-dollar-sign,icon-product,icon-share,icon-support,icon-truck}");`.

**Breaking behavior:** v3's `safelist` accepted regex patterns like `{ pattern: /icon-/ }`. v4's `@source inline(...)` does **not** support regex. If your custom package relied on a regex safelist, you must either enumerate every class explicitly, or move the class definitions out of `@layer components` so they are not tree-shaken.

#### The Default Font Stack Changed

v4 dropped `system-ui` from the front of its default `--font-sans`, which on Linux and Windows falls through to a different face entirely — Roboto or Arial rather than the desktop UI font. Admin and Shop pin the v3 stack back:

```css
@theme {
    --font-sans: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
}
```

If your theme overrides `app.css` from scratch and did not set its own font, add the same declaration or the whole storefront shifts typeface.

#### Icon Classes Renamed and Restructured

The icon fonts moved from plain classes inside `@layer components` to Tailwind utilities. Each glyph is declared once in `@theme` and emitted by a single functional utility:

```diff
- @layer components {
-     .icon-cart:before { content: "\e90c"; }
- }
+ @theme {
+     --icon-cart: "\e90c";
+ }
+
+ @utility icon-* {
+     &::before { content: --value(--icon-*); }
+ }
```

They remain utilities rather than plain classes on purpose — the admin sets icons through variants such as `peer-checked:icon-checked` and `rtl:icon-sort-left`, which a plain class cannot carry.

**Renamed icons.** Icons named after a number rather than what they depict were renamed. If your theme or extension uses the old names, update them:

| Package | Old | New | Glyph |
|---------|-----|-----|-------|
| Admin | `icon-cancel-1` | `icon-close` | plain ✕ (`icon-cancel` still carries the circled ✕) |
| Admin | `icon-customer-2` | `icon-customer` | person |
| Admin | `icon-checkbox-partical` | `icon-checkbox-partial` | spelling fix |
| Shop | `icon-filter-1` | `icon-funnel` | funnel (`icon-filter` still carries the sliders) |
| Shop | `icon-compare-1` | `icon-swap` | curved crossing arrows (`icon-compare` is unchanged) |
| Shop | `icon-sort-1` | `icon-sort` | descending lines |

> [!IMPORTANT]
> Two of those renames took a name that already existed. `icon-customer` and `icon-sort` still work, but now draw the glyph their `-2` / `-1` sibling used to draw, and the glyph they drew in v2.4 is no longer declared. A custom view using either name will render a different icon rather than none, which is easy to miss. Their old glyphs are still in the font files and can be declared under a name of your own:
>
> ```css
> @theme {
>     --icon-customer-outline: "\e912";  /* the v2.4 icon-customer */
>     --icon-sort-grey:        "\e937";  /* the v2.4 icon-sort */
> }
> ```

**Removed icons.** Glyphs that no core view referenced were dropped from the stylesheets so they no longer ship as dead CSS — 10 from Admin, 15 from Shop, 2 from Installer:

| Package | Removed |
|---------|---------|
| Admin | `icon-add-customer`, `icon-ar`, `icon-clip`, `icon-edit-save`, `icon-order-back`, `icon-product-1`, `icon-refund`, `icon-setting`, `icon-tick`, `icon-zoom` |
| Shop | `icon-Free-Shipping`, `icon-add-new`, `icon-astreisk`, `icon-box-fill`, `icon-camera-fill`, `icon-dislike`, `icon-email`, `icon-filter-fill`, `icon-heart-1`, `icon-heart-2`, `icon-left-arrow`, `icon-like`, `icon-right-arrow`, `icon-sort-by`, `icon-tick` |
| Installer | `icon-arrow-down`, `icon-view` |

The glyphs are still in the font files; if your package uses one, declare it in your own CSS:

```css
@theme {
    --icon-refund: "\e948";
}
```

**Safelisting.** With the scan root at `src/`, only names that no file in the package mentions still need `@source inline(...)`; in the storefront that is the five icons named by the SocialShare view and by the theme content the installer seeds.

#### Plugin Registration Changed

v3 registered plugins in JavaScript. v4 registers them in CSS via the `@plugin` directive:

```diff
- // tailwind.config.js
- module.exports = {
-     plugins: [
-         require('@tailwindcss/forms'),
-         require('@tailwindcss/typography'),
-     ],
- };
+ /* app.css */
+ @plugin "@tailwindcss/forms";
+ @plugin "@tailwindcss/typography";
```

Bagisto's core `Admin`, `Shop`, and `Installer` packages did not ship any Tailwind plugins in v3, so no `@plugin` directives are present. If your custom package registered plugins, migrate them to `@plugin` in your `app.css`.

#### v3 Compatibility Base Layer

v4 removed three implicit defaults that Bagisto relied on. To preserve v3 behavior, each `app.css` now ships a `@layer base` block:

```css
@layer base {
    *,
    ::after,
    ::before,
    ::backdrop,
    ::file-selector-button {
        border-color: var(--color-gray-200, currentColor);
    }

    button:not(:disabled),
    [role="button"]:not(:disabled) {
        cursor: pointer;
    }

    input::placeholder,
    textarea::placeholder {
        @apply text-gray-400;
    }
}
```

- **Default `border-color`**: v3 defaulted to `gray-200`; v4 defaults to `currentColor`. Without the rule above, every element with a bare `border` class would suddenly draw the text color.
- **Button cursor**: v3 set `cursor: pointer` on `<button>`; v4 does not. Without the rule above, bare `<button>` elements without `cursor-pointer` on them would show the arrow cursor.
- **Placeholder color**: v3 drew placeholders in `gray-400`; v4 draws them in the text color at reduced opacity. Without the rule above, placeholders would read as entered text.

If your custom theme overrides `app.css` from scratch, add this block or its equivalent.

#### `@apply` Inside `@keyframes` No Longer Supported

v4's Vite plugin errors out on `@apply` used inside `@keyframes` blocks. Rewrite to plain CSS:

```diff
  @keyframes skeleton {
      0% {
-         @apply bg-[-1250px_0];
+         background-position: -1250px 0;
      }

      100% {
-         @apply bg-[1250px_0];
+         background-position: 1250px 0;
      }
  }
```

If your custom `app.css` or theme file has similar `@apply` calls inside `@keyframes`, `@font-face`, or other non-selector blocks, inline the raw CSS.

#### Tailwind Migration Steps

If you maintain a custom Bagisto theme, extension, or admin package with its own Tailwind assets, do the following:

1. **Update `package.json`** — remove `postcss`, `autoprefixer`, and `tailwindcss@^3`; add `tailwindcss@^4.0.0` and `@tailwindcss/vite@^4.0.0`.

2. **Update `vite.config.js`** — import `tailwindcss` from `@tailwindcss/vite` and add `tailwindcss()` to the `plugins` array.

3. **Delete `postcss.config.*` and `tailwind.config.js`** from your package.

4. **Rewrite `app.css`** — replace `@tailwind` directives with `@import "tailwindcss" source("...");`, pointing at the directory your class names are written under, and translate any `theme.extend.*` values from your old JS config into `@theme` blocks. If you overrode Bagisto's colors, breakpoints, or fonts, register them in `@theme` with `--color-*`, `--breakpoint-*`, `--font-*` variables.

5. **Rebuild frontend assets** as shown in [The Upgrade Itself](#the-upgrade-itself).

6. **Visually verify** the storefront, admin panel, and installer flow. Common regression spots after a v4 upgrade:

   - Missing icons — if custom icon classes drop out, safelist them with `@source inline("icon-name-a icon-name-b ...");` in your `app.css`.
   - The wrong icon in the right place — check any use of `icon-customer` or `icon-sort` against the renames above.
   - Elements with bare `border` render the wrong color — confirm the base-layer compat rule (see the "v3 Compatibility Base Layer" section above) is present.
   - Custom buttons without a `cursor-pointer` class show the arrow cursor — confirm the same base-layer rule.
   - The whole page in a different typeface — see "The Default Font Stack Changed" above.

   For any additional utility-class-level breaking changes in your custom Blade templates, refer to the official [Tailwind CSS v4 upgrade guide](https://tailwindcss.com/docs/upgrade-guide).

---

### Product Image Size and Placeholder Settings Removed

**Impact Probability: Low**

Admin → Configuration → Catalog → Products no longer carries the Small, Medium and Large Image sections. The dimensions they held are fixed in the image templates, and the placeholder is the one the theme ships:

| Removed setting | Now |
|---|---|
| `catalog.products.cache_small_image.width` / `.height` | 100 × 100 |
| `catalog.products.cache_medium_image.width` / `.height` | 350 × 360 |
| `catalog.products.cache_large_image.width` / `.height` | 560 × 610 |
| `catalog.products.cache_*_image.url` | the theme's placeholder, falling back to the one Bagisto ships |

A migration deletes the stored rows. A store that had set its own dimensions or uploaded its own placeholder now gets the defaults above, so check your product listings after upgrading. To keep a custom size or placeholder, register an image template in your theme's `customize.image_cache` in `config/themes.php`.

---

### Snake_case Methods Renamed on the Core Helper

**Impact Probability: Low**

Two methods on `Webkul\Core\Core` — the helper reached through `core()` — were the only snake_case names among some sixty camelCase ones:

| Before | After |
|---|---|
| `core()->country_name($code)` | `core()->countryName($code)` |
| `core()->is_empty_date($date)` | `core()->isEmptyDate($date)` |

Both are widely reachable, so a module or a theme that calls either — `country_name()` is common in address templates — has to use the new name. The behaviour and the arguments are unchanged.

---

### CoreConfigRepository Helpers Renamed

**Impact Probability: Low**

Two public helpers on `Webkul\Core\Repositories\CoreConfigRepository` were named after how they worked rather than what they produced:

| Before | After |
|---|---|
| `recursiveArray($formData, $method)` | `flattenToConfigValues($formData, $prefix)` |
| `countDim($array)` | `depthOf($array)` |

Both are internal to saving a configuration form — nothing in Bagisto calls them from outside the repository — but they are public, so a module that calls one has to use the new name. The behaviour and the argument order are unchanged.

---

### Catalog Rule Jobs No Longer Declare a Batch Size

**Impact Probability: Low**

`Webkul\CatalogRule\Jobs\UpdateCreateCatalogRuleIndex` and `DeleteCatalogRuleIndex` no longer carry a `protected const BATCH_SIZE`. Both jobs now hand their product ids to `Webkul\Product\Helpers\Indexers\Price::reindexProducts()`, which batches the reindex itself with the indexer's own `BATCH_SIZE` and loads every relation the type indexers read up front. A subclass of either job that referenced `self::BATCH_SIZE` should call `reindexProducts()` instead of paging through the product repository itself.

The full page cache's price listener now drops every cached page when a reindex touches more than `Webkul\FPC\Listeners\Price::PER_PRODUCT_FORGET_LIMIT` products, rather than resolving each product's pages one by one, so a catalog rule that matches most of the catalogue no longer walks it inside the save request.

---

### Magic AI Model Lists Follow the Providers' Current Models

**Impact Probability: Low**

The model enums under `Webkul\MagicAI\Enums\Models` now list each provider's current models and drop the ones the provider has retired or no longer offers, among them GPT-5.2, Claude Sonnet 4, Gemini 2.5, Imagen, `deepseek-chat` and the Groq-hosted LLaMA models. A migration moves a storefront feature (image search, review translation, checkout message) that was saved on a dropped model onto the replacement its provider recommends, so no manual database work is needed.

Custom code that references a removed enum case, such as `OpenAiModel::GPT52` or `GeminiModel::Imagen4`, must switch to a current case, and a model id passed to `MagicAI::generateContent()` or `MagicAI::generateImage()` must be one the enums still offer, or the request falls back to the SDK's default provider without the key stored in the admin.
