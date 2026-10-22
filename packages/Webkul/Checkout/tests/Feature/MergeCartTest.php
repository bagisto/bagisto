<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Customer\Models\Customer;

// ============================================================================
// Merging A Guest Cart On Login
// ============================================================================

it('should survive a session pointing at a guest cart that no longer exists', function () {
    $customer = Customer::factory()->create();

    $goneCart = CartModel::factory()->create(['is_guest' => true]);

    session()->put('cart', $goneCart);

    $goneCart->delete();

    Cart::mergeCart($customer);

    expect(session()->has('cart'))->toBeFalse();
});
