<?php

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Cart as CartFacadeClass;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Customer\Models\Customer;

/**
 * Delete the cart row from under the facade part way through collecting its totals.
 */
function deleteCartWhileCollectingTotals(): void
{
    Event::listen('checkout.cart.calculate.shipping.tax.after', function ($cart) {
        CartModel::query()->where('id', $cart->id)->delete();
    });
}

// ============================================================================
// Reloading A Cart Whose Row Has Gone
// ============================================================================

it('should hold no cart and keep no session key once the row it pointed at has gone', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    expect(session()->has('cart'))->toBeTrue();

    CartModel::query()->where('id', Cart::getCart()->id)->delete();

    Cart::refreshCart();

    expect(Cart::getCart())->toBeNull()
        ->and(session()->has('cart'))->toBeFalse();
});

// ============================================================================
// Adopting A Guest Cart On Login
// ============================================================================

it('should hold the adopted cart after a login with no cart of its own', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $guestCartId = Cart::getCart()->id;

    $customer = Customer::factory()->create();

    Cart::mergeCart($customer);

    $cart = Cart::getCart();

    expect($cart)->not->toBeNull()
        ->and($cart->id)->toBe($guestCartId)
        ->and($cart->customer_id)->toBe($customer->id)
        ->and((int) $cart->is_guest)->toBe(0)
        ->and(session()->has('cart'))->toBeFalse();
});

// ============================================================================
// A Guest Session Never Picks Up Someone's Cart
// ============================================================================

it('should refuse to load a cart that belongs to a customer from a guest session key', function () {
    $customer = Customer::factory()->create();

    $customerCart = CartModel::factory()->create([
        'customer_id' => $customer->id,
        'is_guest' => 0,
        'is_active' => 1,
    ]);

    $stale = new stdClass;

    $stale->id = $customerCart->id;

    session()->put('cart', $stale);

    app()->forgetInstance(CartFacadeClass::class);

    expect(app(CartFacadeClass::class)->getCart())->toBeNull();
});

it('should hold no cart when the session key is not the shape the cart writes', function () {
    session()->put('cart', 12345);

    app()->forgetInstance(CartFacadeClass::class);

    expect(app(CartFacadeClass::class)->getCart())->toBeNull()
        ->and(session()->has('cart'))->toBeFalse();
});

// ============================================================================
// A Session Key The Merge Must Refuse As Firmly As The Load Does
// ============================================================================

it('should refuse to merge a session cart that has been deactivated', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $strandedId = Cart::getCart()->id;

    CartModel::query()->whereKey($strandedId)->update(['is_active' => 0]);

    Cart::mergeCart(Customer::factory()->create());

    expect(session()->has('cart'))->toBeFalse();

    $this->assertDatabaseHas('cart', [
        'id' => $strandedId,
        'customer_id' => null,
        'is_active' => 0,
    ]);
});

it('should refuse to merge a session cart that already belongs to a customer', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $strandedId = Cart::getCart()->id;

    $owner = Customer::factory()->create();

    CartModel::query()->whereKey($strandedId)->update([
        'customer_id' => $owner->id,
        'is_guest' => 0,
    ]);

    Cart::mergeCart(Customer::factory()->create());

    expect(session()->has('cart'))->toBeFalse();

    $this->assertDatabaseHas('cart', [
        'id' => $strandedId,
        'customer_id' => $owner->id,
    ]);
});

// ============================================================================
// Parking A Cart For Buy Now
// ============================================================================

it('should give back the cart parked once the buy now purchase leaves no cart behind', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    Cart::parkCart();

    expect(session(CartFacadeClass::PARKED_CART_KEY))->toBe($basketId)
        ->and(Cart::getCart())->toBeNull()
        ->and(session()->has('cart'))->toBeFalse();

    $this->assertDatabaseHas('cart', ['id' => $basketId, 'is_active' => 0]);

    Cart::restoreParkedCart();

    expect(Cart::getCart()?->id)->toBe($basketId)
        ->and(session()->has(CartFacadeClass::PARKED_CART_KEY))->toBeFalse()
        ->and(session()->get('cart')->id)->toBe($basketId);

    $this->assertDatabaseHas('cart', ['id' => $basketId, 'is_active' => 1]);
});

it('should keep the cart parked while a buy now cart is still live', function () {
    $basketProduct = $this->createSimpleProduct();

    $buyNowProduct = $this->createSimpleProduct();

    Cart::addProduct($basketProduct, ['product_id' => $basketProduct->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    Cart::parkCart();

    Cart::addProduct($buyNowProduct, ['product_id' => $buyNowProduct->id, 'quantity' => 1]);

    $buyNowId = Cart::getCart()->id;

    Cart::restoreParkedCart();

    expect(Cart::getCart()?->id)->toBe($buyNowId)
        ->and(session(CartFacadeClass::PARKED_CART_KEY))->toBe($basketId);

    $this->assertDatabaseHas('cart', ['id' => $basketId, 'is_active' => 0]);
});

it('should fold a second buy now into the basket already parked', function () {
    $basketProduct = $this->createSimpleProduct();

    $buyNowProduct = $this->createSimpleProduct();

    Cart::addProduct($basketProduct, ['product_id' => $basketProduct->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    Cart::parkCart();

    Cart::addProduct($buyNowProduct, ['product_id' => $buyNowProduct->id, 'quantity' => 1]);

    $buyNowId = Cart::getCart()->id;

    Cart::parkCart();

    expect(session(CartFacadeClass::PARKED_CART_KEY))->toBe($basketId)
        ->and(session(CartFacadeClass::PARKED_CART_KEY))->not->toBe($buyNowId);

    $this->assertDatabaseMissing('cart', ['id' => $buyNowId]);

    Cart::restoreParkedCart();

    expect(Cart::getCart()?->id)->toBe($basketId);

    foreach ([$basketProduct->id, $buyNowProduct->id] as $productId) {
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $basketId,
            'product_id' => $productId,
        ]);
    }
});

it('should refuse to give back a cart that belongs to a customer to a guest session', function () {
    $customer = Customer::factory()->create();

    $cart = CartModel::factory()->create([
        'customer_id' => $customer->id,
        'is_guest' => 0,
        'is_active' => 0,
    ]);

    session()->put(CartFacadeClass::PARKED_CART_KEY, $cart->id);

    Cart::restoreParkedCart();

    expect(Cart::getCart())->toBeNull()
        ->and(session()->has(CartFacadeClass::PARKED_CART_KEY))->toBeFalse();

    $this->assertDatabaseHas('cart', ['id' => $cart->id, 'is_active' => 0]);
});

it('should hand a guest cart parked before a login to the customer who signed in', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    Cart::parkCart();

    $customer = Customer::factory()->create();

    $this->actingAs($customer);

    Cart::restoreParkedCart();

    expect(Cart::getCart()?->id)->toBe($basketId)
        ->and(session()->has('cart'))->toBeFalse();

    $this->assertDatabaseHas('cart', [
        'id' => $basketId,
        'is_active' => 1,
        'is_guest' => 0,
        'customer_id' => $customer->id,
    ]);
});

it('should drop the parked cart id when the cart it names has gone', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    Cart::parkCart();

    CartModel::query()->where('id', $basketId)->delete();

    Cart::restoreParkedCart();

    expect(Cart::getCart())->toBeNull()
        ->and(session()->has(CartFacadeClass::PARKED_CART_KEY))->toBeFalse();
});

it('should hand the basket back and drop the empty cart when a buy now is discarded', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    Cart::parkCart();

    Cart::createCart([]);

    $buyNowId = Cart::getCart()->id;

    Cart::discardBuyNowCart();

    expect(Cart::getCart()?->id)->toBe($basketId)
        ->and(session()->has(CartFacadeClass::PARKED_CART_KEY))->toBeFalse();

    $this->assertDatabaseMissing('cart', ['id' => $buyNowId]);

    $this->assertDatabaseHas('cart', ['id' => $basketId, 'is_active' => 1]);
});

it('should recover a customer parked cart at login once the session pointer is gone', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer);

    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    Cart::parkCart();

    $this->assertDatabaseHas('cart', ['id' => $basketId, 'is_active' => 0, 'is_parked' => 1]);

    session()->forget(CartFacadeClass::PARKED_CART_KEY);

    Cart::mergeCart($customer);

    expect((int) session(CartFacadeClass::PARKED_CART_KEY))->toBe($basketId);
});

// ============================================================================
// The Row Going Away Part Way Through Collecting Totals
// ============================================================================

it('should stop collecting totals once the row has gone rather than total a cart it no longer holds', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    deleteCartWhileCollectingTotals();

    expect(Cart::collectTotals())->toBeInstanceOf(CartFacadeClass::class)
        ->and(Cart::getCart())->toBeNull();
});

it('should report an empty cart when the row goes while the product it is adding is being totalled', function () {
    $product = $this->createSimpleProduct();

    deleteCartWhileCollectingTotals();

    expect(fn () => Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]))
        ->toThrow(Exception::class, trans('shop::app.checkout.cart.index.empty-product'));
});

// ============================================================================
// Activating A Cart By Id
// ============================================================================

it('should activate nothing when the id belongs to no cart', function () {
    $missingId = (int) CartModel::query()->max('id') + 1000;

    Cart::activateCart($missingId);

    expect(Cart::getCart())->toBeNull();
});

it('should hold the cart it activates again after a deactivation', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $cartId = Cart::getCart()->id;

    Cart::deActivateCart();

    Cart::activateCart($cartId);

    expect(Cart::getCart()?->id)->toBe($cartId)
        ->and((int) Cart::getCart()->is_active)->toBe(1);
});

// ============================================================================
// Removing An Item That Has Already Gone
// ============================================================================

it('should remove an item once and answer false the second time it is asked', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $itemId = Cart::getCart()->items->first()->id;

    expect(Cart::removeItem($itemId))->toBeTrue()
        ->and(Cart::removeItem($itemId))->toBeFalse();
});
