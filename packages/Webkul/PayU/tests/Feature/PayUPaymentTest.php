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
        'code'         => 'sales.payment_methods.payu.active',
        'value'        => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.sandbox',
        'value'        => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => 'test_merchant_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_salt',
        'value'        => 'test_merchant_salt',
        'channel_code' => 'default',
    ]);
});

it('redirects back when payu credentials are invalid', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_salt',
        'value'        => '',
        'channel_code' => 'default',
    ]);

    // Act
    $response = $this->get(route('payu.redirect'));

    // Assert
    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('redirects back when cart is not found', function () {
    // Arrange
    Cart::shouldReceive('getCart')->andReturn(null);

    // Act
    $response = $this->get(route('payu.redirect'));

    // Assert
    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('creates payu payment data and returns redirect view', function () {
    // Arrange
    $cart = $this->createCartWithItems('payu');

    // Act
    $response = $this->get(route('payu.redirect'));

    // Assert
    $response->assertOk();

    $response->assertViewIs('payu::checkout.redirect');

    $response->assertViewHas('paymentUrl');

    $response->assertViewHas('paymentData');

    // Verify payment data includes cart_id in udf1
    $paymentData = $response->viewData('paymentData');

    expect($paymentData)->toHaveKey('udf1')
        ->and($paymentData['udf1'])->toBe($cart->id);
});

it('successfully processes payu payment and creates order with invoice', function () {
    // Arrange
    $cart = $this->createCartWithItems('payu');

    $txnid = 'PAYU_TEST123';

    // Mock the PayU payment method
    $mockPayU = $this->mock(PayUPayment::class)->makePartial();

    $mockPayU->shouldReceive('verifyHash')->andReturn(true);

    $this->app->instance(PayUPayment::class, $mockPayU);

    // Prepare success response data
    $paymentData = [
        'txnid'       => $txnid,
        'mihpayid'    => 'MIHPAY_TEST_456',
        'mode'        => 'CC',
        'status'      => 'success',
        'key'         => 'test_merchant_key',
        'amount'      => round($cart->base_grand_total, 2),
        'productinfo' => 'Order #'.$cart->id,
        'firstname'   => $cart->customer_first_name,
        'email'       => $cart->customer_email,
        'hash'        => 'valid_hash_value',
        'udf1'        => $cart->id,
    ];

    // Act
    $response = $this->post(route('payu.success'), $paymentData);

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    // Verify order was created
    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing')
        ->and($order->customer_id)->toBe($cart->customer_id);

    // Verify invoice was created
    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull()
        ->and($invoice->state)->toBe('paid');

    // Verify order transaction was created
    $orderTransaction = OrderTransaction::where('order_id', $order->id)->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->transaction_id)->toBe($txnid)
        ->and($orderTransaction->status)->toBe('success')
        ->and($orderTransaction->type)->toBe('payu');
});

it('handles payment failure gracefully', function () {
    // Arrange
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $customer = Customer::factory()->create();

    $cart = CartModel::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'base_grand_total'    => 100.00,
    ]);

    $txnid = 'PAYU_FAIL_789';

    // Act
    $response = $this->post(route('payu.failure'), [
        'txnid'  => $txnid,
        'status' => 'failure',
        'error'  => 'Payment declined',
        'udf1'   => $cart->id,
    ]);

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    // Verify no order was created
    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->toBeNull();
});

it('redirects to cart when hash verification fails', function () {
    // Arrange
    $cart = CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    $txnid = 'PAYU_INVALID_HASH';

    // Mock invalid hash verification
    $mockPayU = $this->mock(PayUPayment::class)->makePartial();

    $mockPayU->shouldReceive('verifyHash')->andReturn(false);

    $this->app->instance(PayUPayment::class, $mockPayU);

    // Act
    $response = $this->post(route('payu.success'), [
        'txnid'  => $txnid,
        'status' => 'success',
        'hash'   => 'invalid_hash',
        'udf1'   => $cart->id,
    ]);

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('handles payment cancellation', function () {
    // Arrange
    $cart = CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    $txnid = 'PAYU_CANCEL_101';

    // Act
    $response = $this->post(route('payu.cancel'), [
        'txnid'  => $txnid,
        'status' => 'userCancelled',
        'udf1'   => $cart->id,
    ]);

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('warning');

    // Verify no order was created
    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->toBeNull();
});
