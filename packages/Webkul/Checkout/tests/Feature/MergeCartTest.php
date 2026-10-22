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

it('should keep holding the merged cart once the guest cart it merged into it has been taken away', function () {
    $guestProduct = $this->createSimpleProduct();

    $customerProduct = $this->createSimpleProduct();

    Cart::addProduct($guestProduct, ['product_id' => $guestProduct->id, 'quantity' => 1]);

    $guestCartId = Cart::getCart()->id;

    $customer = Customer::factory()->create();

    $customerCart = CartModel::factory()->create([
        'customer_id' => $customer->id,
        'is_guest' => 0,
        'is_active' => 1,
    ]);

    Cart::setCart($customerCart);

    Cart::addProduct($customerProduct, ['product_id' => $customerProduct->id, 'quantity' => 1]);

    Cart::mergeCart($customer);

    expect(Cart::getCart()?->id)->toBe($customerCart->id)
        ->and(session()->has('cart'))->toBeFalse();

    $this->assertDatabaseHas('cart', ['id' => $customerCart->id, 'is_active' => 1]);

    $this->assertDatabaseMissing('cart', ['id' => $guestCartId]);

    foreach ([$guestProduct->id, $customerProduct->id] as $productId) {
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $customerCart->id,
            'product_id' => $productId,
        ]);
    }
});
