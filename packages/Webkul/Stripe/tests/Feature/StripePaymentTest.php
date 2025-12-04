<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Models\CoreConfig;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;
use Webkul\Stripe\Enums\StripeTransactionStatus;
use Webkul\Stripe\Models\StripeTransaction;
use Webkul\Stripe\Payment\Stripe;

beforeEach(function () {
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.stripe.active',
        'value'        => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.stripe.sandbox',
        'value'        => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.stripe.api_test_key',
        'value'        => 'sk_test_fake_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value'        => 'pk_test_fake_key',
        'channel_code' => 'default',
    ]);
});

it('redirects to cart when stripe credentials are invalid', function () {
    // Arrange - Override with empty credentials
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.stripe.api_test_key',
        'value'        => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value'        => '',
        'channel_code' => 'default',
    ]);

    // Act
    $response = $this->get(route('stripe.standard.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when cart is not found', function () {
    // Arrange
    Cart::shouldReceive('getCart')->andReturn(null);

    // Act
    $response = $this->get(route('stripe.standard.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when session id is missing on success callback', function () {
    // Act
    $response = $this->get(route('stripe.payment.success'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when stripe transaction is not found', function () {
    // Act
    $response = $this->get(route('stripe.payment.success', ['session_id' => 'invalid_session']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('updates transaction status to cancelled on cancel callback', function () {
    // Arrange
    $transaction = StripeTransaction::create([
        'cart_id'    => 1,
        'session_id' => 'cs_test_cancel_123',
        'amount'     => 100.00,
        'status'     => StripeTransactionStatus::PENDING,
    ]);

    // Act
    $response = $this->get(route('stripe.payment.cancel', ['session_id' => 'cs_test_cancel_123']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    $transaction->refresh();

    expect($transaction->status)->toBe(StripeTransactionStatus::CANCELLED);
});

it('shows error message on payment cancellation', function () {
    // Act
    $response = $this->get(route('stripe.payment.cancel', ['session_id' => 'cs_test_123']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when cart is already processed', function () {
    // Arrange
    $cart = $this->createCartWithItems('stripe', [
        'is_active'        => 0,
        'base_grand_total' => 100.00,
        'grand_total'      => 100.00,
    ]);

    $transaction = StripeTransaction::create([
        'cart_id'    => $cart->id,
        'session_id' => 'cs_test_already_processed',
        'amount'     => 100.00,
        'status'     => StripeTransactionStatus::PENDING,
    ]);

    $mockSession = (object) [
        'id'             => 'cs_test_already_processed',
        'payment_intent' => 'pi_test_123',
        'payment_status' => 'paid',
        'status'         => 'complete',
    ];

    $stripeMock = $this->mock(Stripe::class)->makePartial();

    $stripeMock->shouldReceive('retrieveCheckoutSession')
        ->with('cs_test_already_processed')
        ->andReturn($mockSession);

    $this->app->instance(Stripe::class, $stripeMock);

    // Act
    $response = $this->get(route('stripe.payment.success', ['session_id' => 'cs_test_already_processed']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    expect($response->getSession()->get('error'))->toContain('cart');
});

it('successfully processes stripe payment and creates order with invoice', function () {
    // Arrange
    $cart = $this->createCartWithItems('stripe');

    $transaction = StripeTransaction::create([
        'cart_id'    => $cart->id,
        'session_id' => 'cs_test_success_123',
        'amount'     => $cart->base_grand_total,
        'status'     => StripeTransactionStatus::PENDING,
    ]);

    $mockSession = (object) [
        'id'             => 'cs_test_success_123',
        'payment_intent' => 'pi_test_123',
        'payment_status' => 'paid',
        'status'         => 'complete',
    ];

    $stripeMock = $this->mock(Stripe::class)->makePartial();

    $stripeMock->shouldReceive('retrieveCheckoutSession')
        ->with('cs_test_success_123')
        ->andReturn($mockSession);

    $this->app->instance(Stripe::class, $stripeMock);

    // Act
    $response = $this->get(route('stripe.payment.success', ['session_id' => 'cs_test_success_123']));

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $response->assertSessionHas('success');

    $response->assertSessionHas('order_id');

    // Verify order was created
    $order = Order::where('customer_id', $cart->customer_id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    // Verify transaction was updated
    $transaction->refresh();

    expect($transaction->status)->toBe(StripeTransactionStatus::COMPLETED)
        ->and($transaction->payment_intent_id)->toBe('pi_test_123')
        ->and($transaction->order_id)->toBe($order->id);

    // Verify order transaction was created
    $orderTransaction = OrderTransaction::where('transaction_id', 'pi_test_123')->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->order_id)->toBe($order->id)
        ->and($orderTransaction->status)->toBe(StripeTransactionStatus::COMPLETED->value);

    // Verify invoice was created
    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull();

    // Verify cart was deactivated
    $cart->refresh();

    expect($cart->is_active)->toBe(0);
});
