<?php

use Illuminate\Testing\TestResponse;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Customer\Models\Customer;
use Webkul\PayU\Payment\PayU as PayUPayment;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

beforeEach(function () {
    $this->setConfig([
        'sales.payment_methods.payu.active' => '1',
        'sales.payment_methods.payu.sandbox' => '1',
        'sales.payment_methods.payu.merchant_key' => 'test_merchant_key',
        'sales.payment_methods.payu.merchant_salt' => 'test_merchant_salt',
    ]);
});

// ============================================================================
// Redirect
// ============================================================================

it('should redirect back when the PayU credentials are invalid', function () {
    $this->setConfig([
        'sales.payment_methods.payu.merchant_key' => '',
        'sales.payment_methods.payu.merchant_salt' => '',
    ]);

    $response = $this->get(route('payu.redirect'));

    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('should redirect back when the cart is not found', function () {
    Cart::shouldReceive('getCart')->andReturn(null);

    $response = $this->get(route('payu.redirect'));

    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('should create the PayU payment data and return the redirect view', function () {
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    $response = $this->get(route('payu.redirect'));

    $response->assertOk();

    $response->assertViewIs('payu::checkout.redirect');

    $response->assertViewHas('paymentUrl');

    $response->assertViewHas('paymentData');

    $paymentData = $response->viewData('paymentData');

    expect($paymentData)->toHaveKey('udf1')
        ->and($paymentData['udf1'])->toBe($cart->id);
});

it('should refuse a cart in a currency PayU does not settle', function () {
    $this->createCartWithItems('payu', ['base_currency_code' => 'JPY']);

    $response = $this->get(route('payu.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

// ============================================================================
// Payment Callbacks
// ============================================================================

it('should process the PayU payment and create the order with an invoice', function () {
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    $txnid = 'PAYU_TEST123';

    $mockPayU = $this->mock(PayUPayment::class)->makePartial();

    $mockPayU->shouldReceive('verifyHash')->andReturn(true);

    $this->app->instance(PayUPayment::class, $mockPayU);

    $paymentData = [
        'txnid' => $txnid,
        'mihpayid' => 'MIHPAY_TEST_456',
        'mode' => 'CC',
        'status' => 'success',
        'key' => 'test_merchant_key',
        'amount' => round($cart->base_grand_total, 2),
        'productinfo' => 'Order #'.$cart->id,
        'firstname' => $cart->customer_first_name,
        'email' => $cart->customer_email,
        'hash' => 'valid_hash_value',
        'udf1' => $cart->id,
    ];

    $response = $this->post(route('payu.success'), $paymentData);

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
        ->and($orderTransaction->transaction_id)->toBe($txnid)
        ->and($orderTransaction->status)->toBe('success')
        ->and($orderTransaction->type)->toBe('payu');
});

it('should handle a payment failure gracefully', function () {
    $customer = Customer::factory()->create();

    $cart = CartModel::factory()->create([
        'customer_id' => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'customer_email' => $customer->email,
        'is_guest' => false,
        'base_grand_total' => 100.00,
    ]);

    $txnid = 'PAYU_FAIL_789';

    $response = $this->post(route('payu.failure'), [
        'txnid' => $txnid,
        'status' => 'failure',
        'error' => 'Payment declined',
        'udf1' => $cart->id,
    ]);

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    $order = Order::query()->where('cart_id', $cart->id)->first();

    expect($order)->toBeNull();
});

it('should redirect to the cart when the hash verification fails', function () {
    $cart = CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    $txnid = 'PAYU_INVALID_HASH';

    $mockPayU = $this->mock(PayUPayment::class)->makePartial();

    $mockPayU->shouldReceive('verifyHash')->andReturn(false);

    $this->app->instance(PayUPayment::class, $mockPayU);

    $response = $this->post(route('payu.success'), [
        'txnid' => $txnid,
        'status' => 'success',
        'hash' => 'invalid_hash',
        'udf1' => $cart->id,
    ]);

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should handle a payment cancellation', function () {
    $cart = CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    $txnid = 'PAYU_CANCEL_101';

    $response = $this->post(route('payu.cancel'), [
        'txnid' => $txnid,
        'status' => 'userCancelled',
        'udf1' => $cart->id,
    ]);

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('warning');

    $order = Order::query()->where('cart_id', $cart->id)->first();

    expect($order)->toBeNull();
});

// ============================================================================
// Payment Verification
// ============================================================================

/**
 * The response PayU posts back for a cart paid in full.
 */
function payuResponseFor(object $cart, array $overrides = []): array
{
    return array_merge([
        'txnid' => 'PAYU_TEST_'.$cart->id,
        'mihpayid' => 'MIHPAY_TEST_456',
        'mode' => 'CC',
        'status' => 'success',
        'key' => 'test_merchant_key',
        'amount' => round($cart->base_grand_total, 2),
        'productinfo' => 'Order #'.$cart->id,
        'firstname' => $cart->customer_first_name,
        'email' => $cart->customer_email,
        'hash' => 'valid_hash_value',
        'udf1' => $cart->id,
    ], $overrides);
}

/**
 * Post a PayU response whose hash the gateway accepts.
 */
function payuSuccess(array $response): TestResponse
{
    $mockPayU = test()->mock(PayUPayment::class)->makePartial();

    $mockPayU->shouldReceive('verifyHash')->andReturn(true);

    app()->instance(PayUPayment::class, $mockPayU);

    return test()->post(route('payu.success'), $response);
}

it('should refuse a response PayU did not mark successful', function () {
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    payuSuccess(payuResponseFor($cart, ['status' => 'failure']))
        ->assertRedirect(route('shop.checkout.cart.index'));

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should refuse a response paying less than the cart total', function () {
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    payuSuccess(payuResponseFor($cart, ['amount' => 1.00]))
        ->assertRedirect(route('shop.checkout.cart.index'));

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should refuse a response whose transaction already paid for an order', function () {
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    payuSuccess(payuResponseFor($cart))->assertRedirect(route('shop.checkout.onepage.success'));

    $replayCart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    payuSuccess(payuResponseFor($replayCart, [
        'txnid' => 'PAYU_TEST_'.$cart->id,
        'udf1' => $replayCart->id,
    ]))->assertRedirect(route('shop.checkout.cart.index'));

    expect(Order::query()->where('cart_id', $replayCart->id)->exists())->toBeFalse();
});
