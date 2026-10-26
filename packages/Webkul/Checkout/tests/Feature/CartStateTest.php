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
// Isolated Buy Now Carts
// ============================================================================

it('should create an isolated buy now cart and mirror its product into the basket', function () {
    $basketProduct = $this->createSimpleProduct();

    $buyNowProduct = $this->createSimpleProduct();

    Cart::addProduct($basketProduct, ['product_id' => $basketProduct->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    $buyNowCart = Cart::createBuyNowCart($buyNowProduct, ['product_id' => $buyNowProduct->id, 'quantity' => 1]);

    expect(Cart::getCart()?->id)->toBe($basketId)
        ->and($buyNowCart->id)->not->toBe($basketId)
        ->and((bool) $buyNowCart->is_buy_now)->toBeTrue()
        ->and((bool) $buyNowCart->is_active)->toBeFalse();

    $this->assertDatabaseHas('cart', ['id' => $basketId, 'is_active' => 1]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $buyNowCart->id, 'product_id' => $buyNowProduct->id]);

    $this->assertDatabaseMissing('cart_items', ['cart_id' => $buyNowCart->id, 'product_id' => $basketProduct->id]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketId, 'product_id' => $basketProduct->id]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketId, 'product_id' => $buyNowProduct->id]);
});

it('should subtract only the bought quantity from a basket line the buy now shares', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 2]);

    $basketId = Cart::getCart()->id;

    $buyNowCart = Cart::createBuyNowCart($product, ['product_id' => $product->id, 'quantity' => 1]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketId, 'product_id' => $product->id, 'quantity' => 3]);

    Cart::removeBuyNowItemsFromBasket($buyNowCart->id);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketId, 'product_id' => $product->id, 'quantity' => 2]);
});

it('should keep every buy now cart independent of the others', function () {
    $basketProduct = $this->createSimpleProduct();

    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    Cart::addProduct($basketProduct, ['product_id' => $basketProduct->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    $firstBuyNow = Cart::createBuyNowCart($first, ['product_id' => $first->id, 'quantity' => 1]);

    $secondBuyNow = Cart::createBuyNowCart($second, ['product_id' => $second->id, 'quantity' => 1]);

    expect($firstBuyNow->id)->not->toBe($secondBuyNow->id)
        ->and(Cart::getCart()?->id)->toBe($basketId);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $firstBuyNow->id, 'product_id' => $first->id]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $secondBuyNow->id, 'product_id' => $second->id]);

    $this->assertDatabaseMissing('cart_items', ['cart_id' => $firstBuyNow->id, 'product_id' => $second->id]);
});

it('should resolve a buy now cart the visitor owns and refuse one it does not', function () {
    $product = $this->createSimpleProduct();

    $buyNowCart = Cart::createBuyNowCart($product, ['product_id' => $product->id, 'quantity' => 1]);

    expect(Cart::useBuyNowCart($buyNowCart->id))->toBeTrue()
        ->and(Cart::getCart()?->id)->toBe($buyNowCart->id);

    $stranger = CartModel::factory()->create([
        'is_active' => 0,
        'is_buy_now' => 1,
    ]);

    expect(Cart::useBuyNowCart($stranger->id))->toBeFalse();
});

it('should refuse to use an ordinary basket as a buy now cart', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    expect(Cart::useBuyNowCart(Cart::getCart()->id))->toBeFalse();
});

it('should hand the basket back untouched when a buy now add fails', function () {
    $basketProduct = $this->createSimpleProduct();

    $outOfStock = $this->setProductStock($this->createSimpleProduct(), 0);

    Cart::addProduct($basketProduct, ['product_id' => $basketProduct->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    expect(fn () => Cart::createBuyNowCart($outOfStock, ['product_id' => $outOfStock->id, 'quantity' => 1]))
        ->toThrow(Exception::class);

    expect(Cart::getCart()?->id)->toBe($basketId);

    $this->assertDatabaseHas('cart', ['id' => $basketId, 'is_active' => 1]);

    $this->assertDatabaseMissing('cart_items', ['product_id' => $outOfStock->id]);
});

it('should deactivate a buy now cart without dropping the basket pointer', function () {
    $basketProduct = $this->createSimpleProduct();

    $buyNowProduct = $this->createSimpleProduct();

    Cart::addProduct($basketProduct, ['product_id' => $basketProduct->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    $buyNowCart = Cart::createBuyNowCart($buyNowProduct, ['product_id' => $buyNowProduct->id, 'quantity' => 1]);

    Cart::useBuyNowCart($buyNowCart->id);

    Cart::deActivateCart();

    expect(session()->get('cart')->id)->toBe($basketId);

    $this->assertDatabaseHas('cart', ['id' => $basketId, 'is_active' => 1]);
});

it('should never point the session basket key at a buy now cart it is set to', function () {
    $basketProduct = $this->createSimpleProduct();

    $buyNowProduct = $this->createSimpleProduct();

    Cart::addProduct($basketProduct, ['product_id' => $basketProduct->id, 'quantity' => 1]);

    $basketId = Cart::getCart()->id;

    $buyNowCart = Cart::createBuyNowCart($buyNowProduct, ['product_id' => $buyNowProduct->id, 'quantity' => 1]);

    Cart::setCart($buyNowCart);

    expect(session()->get('cart')->id)->toBe($basketId)
        ->and(Cart::getCart()?->id)->toBe($buyNowCart->id);
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
