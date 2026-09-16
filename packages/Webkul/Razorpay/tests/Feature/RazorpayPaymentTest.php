<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Razorpay\Payment\RazorpayPayment;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

beforeEach(function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.active' => '1',
        'sales.payment_methods.razorpay.sandbox' => '1',
        'sales.payment_methods.razorpay.test_client_id' => 'rzp_test_fake_key',
        'sales.payment_methods.razorpay.test_client_secret' => 'fake_test_secret',
    ]);
});

// ============================================================================
// Redirect
// ============================================================================

it('should redirect back when the Razorpay credentials are invalid', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.test_client_id' => '',
        'sales.payment_methods.razorpay.test_client_secret' => '',
    ]);

    $response = $this->get(route('razorpay.payment.redirect'));

    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('should redirect back when the cart is not found', function () {
    Cart::shouldReceive('getCart')->andReturn(null);

    $response = $this->get(route('razorpay.payment.redirect'));

    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('should create the Razorpay order and return the drop-in UI view', function () {
    $cart = $this->createCartWithItems('razorpay', ['base_currency_code' => 'INR']);

    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('createOrder')->andReturn([
        'id' => 'order_test123',
        'amount' => 33415,
        'currency' => 'INR',
        'receipt' => 'receipt_'.$cart->id,
    ]);

    $mockRazorpay->shouldReceive('preparePaymentData')->andReturn([
        'key' => 'test_key',
        'amount' => 33415,
        'currency' => 'INR',
        'order_id' => 'order_test123',
    ]);

    $this->app->instance(RazorpayPayment::class, $mockRazorpay);

    $response = $this->get(route('razorpay.payment.redirect'));

    $response->assertOk();

    $response->assertViewIs('razorpay::drop-in-ui');

    $response->assertViewHas('payment');
});

// ============================================================================
// Payment Callback
// ============================================================================

it('should process the Razorpay payment and create the order with an invoice', function () {
    $cart = $this->createCartWithItems('razorpay', ['base_currency_code' => 'INR']);

    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('verifySignature')->andReturn(true);

    $this->app->instance(RazorpayPayment::class, $mockRazorpay);

    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_payment_id' => 'pay_test123',
        'razorpay_order_id' => 'order_test123',
        'razorpay_signature' => 'test_signature',
    ]));

    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $order = Order::query()->where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing')
        ->and($order->customer_id)->toBe($cart->customer_id);

    $invoice = Invoice::query()->where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull()
        ->and($invoice->state)->toBe('paid');

    $orderTransaction = OrderTransaction::query()->where('order_id', $order->id)->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->transaction_id)->toBe('pay_test123')
        ->and($orderTransaction->status)->toBe('captured')
        ->and($orderTransaction->type)->toBe('razorpay');
});

it('should handle a payment failure gracefully', function () {
    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_order_id' => 'order_fail_123',
        'error' => 'payment_failed',
    ]));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should redirect to the cart when the signature verification fails', function () {
    $cart = CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('verifySignature')->andReturn(false);

    $this->app->instance(RazorpayPayment::class, $mockRazorpay);

    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_payment_id' => 'pay_test_456',
        'razorpay_order_id' => 'order_test_456',
        'razorpay_signature' => 'invalid_signature',
    ]));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});
