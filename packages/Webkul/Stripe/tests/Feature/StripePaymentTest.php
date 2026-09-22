<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Models\CoreConfig;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;
use Webkul\Stripe\Payment\Stripe;

beforeEach(function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.active',
        'channel_code' => 'default',
    ], ['value' => '1']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '1']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'channel_code' => 'default',
    ], ['value' => 'sk_test_fake_key']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'channel_code' => 'default',
    ], ['value' => 'pk_test_fake_key']);
});

it('should return to the cart when the stripe credentials are invalid', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'channel_code' => 'default',
    ], ['value' => '']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'channel_code' => 'default',
    ], ['value' => '']);

    $response = $this->get(route('stripe.standard.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should return to the cart when there is no cart', function () {
    Cart::shouldReceive('getCart')->andReturn(null);

    $response = $this->get(route('stripe.standard.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should return to the cart when the success callback has no session id', function () {
    $response = $this->get(route('stripe.payment.success'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should return to the cart when the stripe session is invalid or not found', function () {
    $stripeMock = $this->mock(Stripe::class)->makePartial();

    $stripeMock->shouldReceive('retrieveCheckoutSession')
        ->with('invalid_session')
        ->andReturn(false);

    $this->app->instance(Stripe::class, $stripeMock);

    $response = $this->get(route('stripe.payment.success', ['session_id' => 'invalid_session']));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should show an error when the payment is cancelled', function () {
    $response = $this->get(route('stripe.payment.cancel', ['session_id' => 'cs_test_123']));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should return to the cart when the cart was already processed', function () {
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

it('should place the order with its invoice once the payment succeeds', function () {
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

    expect($cart->is_active)->toBe(0);
});
