<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Models\CoreConfig;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;
use Webkul\Stripe\Payment\Stripe;

beforeEach(function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'value' => 'sk_test_fake_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value' => 'pk_test_fake_key',
        'channel_code' => 'default',
    ]);
});

it('redirects to cart when stripe credentials are invalid', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    $response = $this->get(route('stripe.standard.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when cart is not found', function () {
    Cart::shouldReceive('getCart')->andReturn(null);

    $response = $this->get(route('stripe.standard.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when session id is missing on success callback', function () {
    $response = $this->get(route('stripe.payment.success'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when stripe session is invalid or not found', function () {
    $stripeMock = $this->mock(Stripe::class)->makePartial();

    $stripeMock->shouldReceive('retrieveCheckoutSession')
        ->with('invalid_session')
        ->andReturn(false);

    $this->app->instance(Stripe::class, $stripeMock);

    $response = $this->get(route('stripe.payment.success', ['session_id' => 'invalid_session']));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('shows error message on payment cancellation', function () {
    $response = $this->get(route('stripe.payment.cancel', ['session_id' => 'cs_test_123']));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when cart is already processed', function () {
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

it('successfully processes stripe payment and creates order with invoice', function () {
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

    $order = Order::where('customer_id', $cart->customer_id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    $orderTransaction = OrderTransaction::where('transaction_id', 'pi_test_123')->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->order_id)->toBe($order->id)
        ->and($orderTransaction->status)->toBe('paid');

    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull();

    $cart->refresh();

    expect($cart->is_active)->toBe(false);
});
