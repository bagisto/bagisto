<?php

use Webkul\Checkout\Facades\Cart;

use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

/**
 * One unit of the product in the cart, shipped to a US address by flat rate.
 */
function cartShippedWithFlatRate($product)
{
    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
    ])->assertOk();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge(test()->storefrontAddress(), ['use_for_shipping' => true]),
    ])->assertOk();

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => 'flatrate_flatrate',
    ])->assertOk();

    return Cart::getCart();
}

// ============================================================================
// A Chosen Shipping Rate Does Not Survive A Changed Cart
// ============================================================================

it('should drop the chosen shipping rate when the quantity changes', function () {
    $product = $this->createSimpleProduct();

    $cart = cartShippedWithFlatRate($product);

    expect($cart->selected_shipping_rate)->not->toBeNull();

    putJson(route('shop.api.checkout.cart.update'), [
        'qty' => [$cart->items->first()->id => 100],
    ])->assertOk();

    expect(Cart::getCart()->selected_shipping_rate)->toBeNull();
});

it('should drop the chosen shipping rate when another product is added', function () {
    $product = $this->createSimpleProduct();

    expect(cartShippedWithFlatRate($product)->selected_shipping_rate)->not->toBeNull();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $this->createSimpleProduct()->id,
        'quantity' => 1,
    ])->assertOk();

    expect(Cart::getCart()->selected_shipping_rate)->toBeNull();
});
