<?php

use Webkul\Checkout\Cart as CartFacadeClass;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Customer\Models\Customer;

/**
 * Point the guest session's cart key at the given cart id, the shape the cart writes it in.
 */
function pointSessionCartAt(int $cartId): void
{
    $sessionCart = new stdClass;

    $sessionCart->id = $cartId;

    session()->put('cart', $sessionCart);
}

// ============================================================================
// A Guest Session Never Picks Up Someone's Cart
// ============================================================================

it('should refuse to load a cart that belongs to a customer from a guest session key', function () {
    $customerCart = CartModel::factory()->create([
        'customer_id' => Customer::factory()->create()->id,
        'is_guest' => 0,
        'is_active' => 1,
    ]);

    pointSessionCartAt($customerCart->id);

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

it('should log in without failing when the session key names a cart that has gone', function () {
    pointSessionCartAt(CartModel::query()->max('id') + 1000);

    Cart::mergeCart(Customer::factory()->create());

    expect(session()->has('cart'))->toBeFalse();
});

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
