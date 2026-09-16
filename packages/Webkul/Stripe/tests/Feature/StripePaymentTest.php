<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;
use Webkul\Stripe\Payment\Stripe;

beforeEach(function () {
    $this->setConfig([
        'sales.payment_methods.stripe.active' => '1',
        'sales.payment_methods.stripe.sandbox' => '1',
        'sales.payment_methods.stripe.api_test_key' => 'sk_test_fake_key',
        'sales.payment_methods.stripe.api_test_publishable_key' => 'pk_test_fake_key',
    ]);
});

// ============================================================================
// Redirect
// ============================================================================

it('should redirect to the cart when the Stripe credentials are invalid', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.api_test_key' => '',
        'sales.payment_methods.stripe.api_test_publishable_key' => '',
    ]);

    $response = $this->get(route('stripe.standard.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should redirect to the cart when the cart is not found', function () {
    Cart::shouldReceive('getCart')->andReturn(null);

    $response = $this->get(route('stripe.standard.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

// ============================================================================
// Payment Callbacks
// ============================================================================

it('should redirect to the cart when the session id is missing on the success callback', function () {
    $response = $this->get(route('stripe.payment.success'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should redirect to the cart when the Stripe session is invalid or not found', function () {
    $stripeMock = $this->mock(Stripe::class)->makePartial();

    $stripeMock->shouldReceive('retrieveCheckoutSession')
        ->with('invalid_session')
        ->andReturn(false);

    $this->app->instance(Stripe::class, $stripeMock);

    $response = $this->get(route('stripe.payment.success', ['session_id' => 'invalid_session']));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should show an error message when the payment is cancelled', function () {
    $response = $this->get(route('stripe.payment.cancel', ['session_id' => 'cs_test_123']));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should redirect to the cart when the cart is already processed', function () {
    $cart = $this->createCartWithItems('stripe', [
        'is_active' => 0,
        'base_grand_total' => 100.00,
        'grand_total' => 100.00,
    ]);

    $mockSession = (object) [
        'id' => 'cs_test_already_processed',
        'payment_intent' => 'pi_test_123',
        'payment_status' => 'paid',
        'status' => 'complete',
        'metadata' => (object) ['cart_id' => $cart->id],
    ];

    $stripeMock = $this->mock(Stripe::class)->makePartial();

    $stripeMock->shouldReceive('retrieveCheckoutSession')
        ->with('cs_test_already_processed')
        ->andReturn($mockSession);

    $this->app->instance(Stripe::class, $stripeMock);

    $response = $this->get(route('stripe.payment.success', ['session_id' => 'cs_test_already_processed']));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    expect($response->getSession()->get('error'))->toContain('cart');
});

it('should process the Stripe payment and create the order with an invoice', function () {
    $cart = $this->createCartWithItems('stripe');

    $mockSession = (object) [
        'id' => 'cs_test_success_123',
        'payment_intent' => 'pi_test_123',
        'payment_status' => 'paid',
        'status' => 'complete',
        'metadata' => (object) ['cart_id' => $cart->id],
    ];

    $stripeMock = $this->mock(Stripe::class)->makePartial();

    $stripeMock->shouldReceive('retrieveCheckoutSession')
        ->with('cs_test_success_123')
        ->andReturn($mockSession);

    $this->app->instance(Stripe::class, $stripeMock);

    $response = $this->get(route('stripe.payment.success', ['session_id' => 'cs_test_success_123']));

    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $response->assertSessionHas('success');

    $response->assertSessionHas('order_id');

    $order = Order::query()->where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    $orderTransaction = OrderTransaction::query()->where('transaction_id', 'pi_test_123')->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->order_id)->toBe($order->id)
        ->and($orderTransaction->status)->toBe('paid')
        ->and(Invoice::query()->where('order_id', $order->id)->first())->not->toBeNull()
        ->and($cart->refresh()->is_active)->toBeFalse();
});
