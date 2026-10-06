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
    // Arrange
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
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

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
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    $txnid = 'PAYU_TEST123';

    // Mock the PayU payment method
    $mockPayU = $this->mock(PayUPayment::class)->makePartial();

    $mockPayU->shouldReceive('verifyHash')->andReturn(true);

    $this->app->instance(PayUPayment::class, $mockPayU);

    // Prepare success response data
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
        'customer_id' => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'customer_email' => $customer->email,
        'is_guest' => 0,
        'base_grand_total' => 100.00,
    ]);

    $txnid = 'PAYU_FAIL_789';

    // Act
    $response = $this->post(route('payu.failure'), [
        'txnid' => $txnid,
        'status' => 'failure',
        'error' => 'Payment declined',
        'udf1' => $cart->id,
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
        'txnid' => $txnid,
        'status' => 'success',
        'hash' => 'invalid_hash',
        'udf1' => $cart->id,
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
        'txnid' => $txnid,
        'status' => 'userCancelled',
        'udf1' => $cart->id,
    ]);

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('warning');

    // Verify no order was created
    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->toBeNull();
});

/**
 * The response PayU posts for a paid transaction, to the browser and to the webhook alike.
 */
function payuPaidResponse($cart, string $txnid): array
{
    return [
        'txnid' => $txnid,
        'mihpayid' => 'MIHPAY_'.$txnid,
        'mode' => 'UPI',
        'status' => 'success',
        'key' => 'test_merchant_key',
        'amount' => round($cart->base_grand_total, 2),
        'productinfo' => 'Order #'.$cart->id,
        'firstname' => $cart->customer_first_name,
        'email' => $cart->customer_email,
        'hash' => 'valid_hash_value',
        'udf1' => $cart->id,
    ];
}

/**
 * Accept every hash, so a test states what the handler does with a verified response.
 */
function payuAcceptsEveryHash(): void
{
    $payU = test()->mock(PayUPayment::class)->makePartial();

    $payU->shouldReceive('verifyHash')->andReturn(true);

    app()->instance(PayUPayment::class, $payU);
}

it('places the order from the webhook when the customer never returns to the store', function () {
    // Arrange
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    payuAcceptsEveryHash();

    // Act
    $response = $this->post(route('payu.webhook'), payuPaidResponse($cart, 'PAYU_WEBHOOK_1'));

    // Assert
    $response->assertOk();

    $response->assertJson(['status' => 'order_placed']);

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    expect(Invoice::where('order_id', $order->id)->first())->not->toBeNull();

    expect(OrderTransaction::where('order_id', $order->id)->first()?->transaction_id)->toBe('PAYU_WEBHOOK_1');
});

it('refuses a webhook whose hash does not verify', function () {
    // Arrange
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    $payU = $this->mock(PayUPayment::class)->makePartial();

    $payU->shouldReceive('verifyHash')->andReturn(false);

    $this->app->instance(PayUPayment::class, $payU);

    // Act
    $response = $this->post(route('payu.webhook'), payuPaidResponse($cart, 'PAYU_WEBHOOK_2'));

    // Assert
    $response->assertStatus(400);

    $response->assertJson(['status' => 'invalid_hash']);

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('places no second order when the webhook follows the customer back to the store', function () {
    // Arrange
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    payuAcceptsEveryHash();

    $payload = payuPaidResponse($cart, 'PAYU_WEBHOOK_3');

    $this->post(route('payu.success'), $payload)
        ->assertRedirect(route('shop.checkout.onepage.success'));

    // Act
    $response = $this->post(route('payu.webhook'), $payload);

    // Assert
    $response->assertOk();

    $response->assertJson(['status' => 'order_placed']);

    expect(Order::where('cart_id', $cart->id)->count())->toBe(1);
});

it('places no order from a webhook reporting a payment that did not succeed', function () {
    // Arrange
    $cart = $this->createCartWithItems('payu', ['base_currency_code' => 'INR']);

    payuAcceptsEveryHash();

    // Act
    $response = $this->post(route('payu.webhook'), array_merge(
        payuPaidResponse($cart, 'PAYU_WEBHOOK_4'),
        ['status' => 'failure'],
    ));

    // Assert
    $response->assertOk();

    $response->assertJson(['status' => 'order_not_placed']);

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('refuses a cart in a currency payu does not settle', function () {
    // Arrange
    // The amount is sent rounded to two decimal places, so a currency with a different number
    // of them would be charged wrongly rather than refused.
    $this->createCartWithItems('payu', ['base_currency_code' => 'JPY']);

    // Act
    $response = $this->get(route('payu.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});
