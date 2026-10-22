<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Customer\Models\Customer;

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

    app()->forgetInstance(Webkul\Checkout\Cart::class);

    expect(app(Webkul\Checkout\Cart::class)->getCart())->toBeNull();
});
