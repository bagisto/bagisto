<?php

use Webkul\Paypal\Http\Controllers\StandardController;
use Webkul\Sales\Models\Order;

use function Pest\Laravel\get;
use function Pest\Laravel\withSession;

// ============================================================================
// Standard Redirect
// ============================================================================

it('should send a guest who has to sign in back to checkout instead of starting a PayPal payment', function () {
    $this->setConfig('sales.checkout.shopping_cart.allow_guest_checkout', '0');

    $this->createCartWithItems('paypal_standard');

    get(route('paypal.standard.redirect'))
        ->assertRedirect(route('shop.checkout.onepage.index'))
        ->assertSessionMissing(StandardController::INTENT_KEY);
});

// ============================================================================
// Standard Return
// ============================================================================

it('should not place an order for a return this session never started a payment for', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    get(route('paypal.standard.success'))
        ->assertRedirect(route('shop.checkout.cart.index'))
        ->assertSessionHas('error', trans('paypal::app.errors.payment-not-confirmed'));

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse()
        ->and($cart->refresh()->is_active)->toBeTruthy();
});

it('should not place an order for a cart the started payment was not for', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    withSession([StandardController::INTENT_KEY => $cart->id + 1000])
        ->get(route('paypal.standard.success'))
        ->assertRedirect(route('shop.checkout.cart.index'));

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should place the order once the payment was started for that cart', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    withSession([StandardController::INTENT_KEY => $cart->id])
        ->get(route('paypal.standard.success'))
        ->assertRedirect(route('shop.checkout.onepage.success'));

    expect(Order::query()->where('cart_id', $cart->id)->count())->toBe(1);
});

it('should send a repeated return to the order it already placed rather than failing', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    withSession([StandardController::INTENT_KEY => $cart->id])
        ->get(route('paypal.standard.success'))
        ->assertRedirect(route('shop.checkout.onepage.success'));

    withSession([StandardController::INTENT_KEY => $cart->id])
        ->get(route('paypal.standard.success'))
        ->assertRedirect(route('shop.checkout.onepage.success'));

    expect(Order::query()->where('cart_id', $cart->id)->count())->toBe(1);
});
