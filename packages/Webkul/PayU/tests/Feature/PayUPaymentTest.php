<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\PayU\Payment\PayU as PayUPayment;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

beforeEach(function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => 'test_merchant_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_salt',
        'value' => 'test_merchant_salt',
        'channel_code' => 'default',
    ]);
});

it('redirects back when payu credentials are invalid', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_salt',
        'value' => '',
        'channel_code' => 'default',
    ]);

    $response = $this->get(route('payu.redirect'));

    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('redirects back when cart is not found', function () {
    Cart::shouldReceive('getCart')->andReturn(null);

    $response = $this->get(route('payu.redirect'));

    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('creates payu payment data and returns redirect view', function () {
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

it('successfully processes payu payment and creates order with invoice', function () {
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

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing')
        ->and($order->customer_id)->toBe($cart->customer_id);

    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull()
        ->and($invoice->state)->toBe('paid');

    $orderTransaction = OrderTransaction::where('order_id', $order->id)->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->transaction_id)->toBe($txnid)
        ->and($orderTransaction->status)->toBe('success')
        ->and($orderTransaction->type)->toBe('payu');
});

it('handles payment failure gracefully', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

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

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->toBeNull();
});

it('redirects to cart when hash verification fails', function () {
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

it('handles payment cancellation', function () {
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

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->toBeNull();
});

it('refuses a cart in a currency payu does not settle', function () {
    $this->createCartWithItems('payu', ['base_currency_code' => 'JPY']);

    $response = $this->get(route('payu.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});
