<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Webkul\Checkout\Facades\Cart;
use Webkul\PayGlocal\Enums\PayGlocalPaymentStatus;
use Webkul\PayGlocal\Helpers\Crypto;
use Webkul\PayGlocal\Payment\PayGlocal;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

/**
 * The claims PayGlocal signs into the `x-gl-token` it posts back, in the shape it really sends:
 * the reference, the outcome, and the status url to confirm the outcome against.
 */
function payGlocalCallbackClaims($cart): array
{
    return [
        'gid' => 'gl_o-test_gid',
        'statusUrl' => 'https://api.uat.pygcl.com/gl/v1/payments/gl_o-test_gid/status?x-gl-token=token',
        'Amount' => (string) $cart->base_grand_total,
        'merchantTxnId' => 'PGL'.$cart->id.'TTEST',
        'paymentMethod' => 'CARD',
        'status' => PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value,
        'x-gl-merchantId' => 'test_merchant',
    ];
}

/**
 * The body PayGlocal's status API answers with, in the shape it really sends. That call is what
 * an order is built from, so a payment is simulated by answering it rather than by the token.
 */
function payGlocalStatusResponse($cart, string $status, array $dataOverrides = []): array
{
    return [
        'gid' => 'gl_o-test_gid',
        'status' => $status,
        'message' => 'Transaction is '.strtolower($status),
        'reasonCode' => 'GL-201-001',
        'data' => array_merge([
            'gid' => 'gl_o-test_gid',
            'payment-method' => 'CARD',
            'Amount' => (string) $cart->base_grand_total,
            'txnCurrency' => $cart->base_currency_code,
            'merchantTxnId' => 'PGL'.$cart->id.'TTEST',
            'status' => $status,
        ], $dataOverrides),
        'errors' => null,
    ];
}

/**
 * Answer the status call for a captured payment on the given cart.
 */
function mockPayGlocalConfirmedStatus($cart, array $dataOverrides = []): void
{
    test()->payGlocalMock->shouldReceive('getTransactionStatus')
        ->with('https://api.uat.pygcl.com/gl/v1/payments/gl_o-test_gid/status?x-gl-token=token')
        ->andReturn(payGlocalStatusResponse($cart, PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value, $dataOverrides));
}

/**
 * Stand in for the signed token PayGlocal posts. Passing null models a token that does not
 * verify, which must never settle anything.
 */
function mockPayGlocalCallbackToken(?array $claims): void
{
    $cryptoMock = test()->mock(Crypto::class)->makePartial();

    $cryptoMock->shouldReceive('verify')->andReturn($claims);

    app()->instance(Crypto::class, $cryptoMock);
}

/**
 * Move the stored cart totals away from what its items add up to, leaving the given instance as it was.
 */
function driftPayGlocalCartTotals($cart, float $amount): void
{
    DB::table('cart')->where('id', $cart->id)->update([
        'grand_total' => $cart->grand_total + $amount,
        'base_grand_total' => $cart->base_grand_total + $amount,
        'sub_total' => $cart->sub_total + $amount,
        'base_sub_total' => $cart->base_sub_total + $amount,
    ]);
}

beforeEach(function () {
    Http::preventStrayRequests();

    $this->setConfig([
        'sales.payment_methods.payglocal.active' => '1',
        'sales.payment_methods.payglocal.sandbox' => '1',
        'sales.payment_methods.payglocal.merchant_id' => 'test_merchant',
        'sales.payment_methods.payglocal.public_key_id' => 'test_public_kid',
        'sales.payment_methods.payglocal.private_key_id' => 'test_private_kid',
        'sales.payment_methods.payglocal.payglocal_public_key' => 'fake_public_key',
        'sales.payment_methods.payglocal.merchant_private_key' => 'fake_private_key',
        'sales.payment_methods.payglocal.accepted_currencies' => 'USD,INR',
    ]);

    $this->payGlocalMock = $this->mock(PayGlocal::class)->makePartial();

    $this->payGlocalMock->shouldReceive('hasUsableKeys')->andReturn(true);

    $this->app->instance(PayGlocal::class, $this->payGlocalMock);
});

// ============================================================================
// Redirect
// ============================================================================

it('should redirect to the cart when the configured keys cannot be used', function () {
    $payGlocalMock = $this->mock(PayGlocal::class)->makePartial();

    $payGlocalMock->shouldReceive('hasUsableKeys')->andReturn(false);

    $this->app->instance(PayGlocal::class, $payGlocalMock);

    $response = $this->get(route('payglocal.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should redirect to the cart when the cart currency is not accepted', function () {
    $cart = $this->createCartWithItems('payglocal');

    Cart::shouldReceive('getCart')->andReturn($cart);

    $this->payGlocalMock->shouldReceive('isCurrencySupported')->andReturn(false);

    $response = $this->get(route('payglocal.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should redirect to the cart when the cart is not found', function () {
    Cart::shouldReceive('getCart')->andReturn(null);

    $response = $this->get(route('payglocal.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should send the customer to PayGlocal without recording anything of its own', function () {
    $cart = $this->createCartWithItems('payglocal');

    Cart::shouldReceive('getCart')->andReturn($cart);

    $this->payGlocalMock->shouldReceive('initiatePayment')
        ->andReturn([
            'gid' => 'gl_o-test_gid',
            'redirectUrl' => 'https://api.uat.payglocal.in/gl/payflow-ui/?x-gl-token=token',
        ]);

    $response = $this->get(route('payglocal.redirect'));

    $response->assertRedirect('https://api.uat.payglocal.in/gl/payflow-ui/?x-gl-token=token');
});

it('should report a failure when PayGlocal will not start the payment', function () {
    $cart = $this->createCartWithItems('payglocal');

    Cart::shouldReceive('getCart')->andReturn($cart);

    $this->payGlocalMock->shouldReceive('initiatePayment')->andReturn(null);

    $response = $this->get(route('payglocal.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

// ============================================================================
// Callback
// ============================================================================

it('should settle the payment and create the order with an invoice from the callback', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(payGlocalCallbackClaims($cart));

    mockPayGlocalConfirmedStatus($cart);

    $response = $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    $response->assertRedirect(route('payglocal.success', [
        'gid' => 'gl_o-test_gid',
        'merchantTxnId' => 'PGL'.$cart->id.'TTEST',
    ]));

    $order = Order::query()->where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    $orderTransaction = OrderTransaction::query()->where('transaction_id', 'gl_o-test_gid')->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->order_id)->toBe($order->id)
        ->and($orderTransaction->status)->toBe(PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value)
        ->and((float) $orderTransaction->amount)->toBe((float) $order->base_grand_total)
        ->and(Invoice::query()->where('order_id', $order->id)->first())->not->toBeNull()
        ->and($cart->refresh()->is_active)->toBeFalse();
});

it('should not start a session when receiving the callback', function () {
    $response = $this->post(route('payglocal.callback'));

    expect($response->headers->getCookies())->toBeEmpty();
});

it('should place no order when the callback token is not signed by PayGlocal', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(null);

    $this->post(route('payglocal.callback'), ['x-gl-token' => 'forged']);

    expect(Order::query()->where('cart_id', $cart->id)->first())->toBeNull();
});

it('should place no order when PayGlocal does not confirm the payment', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(payGlocalCallbackClaims($cart));

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(payGlocalStatusResponse($cart, PayGlocalPaymentStatus::ISSUER_DECLINE->value));

    $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    expect(Order::query()->where('cart_id', $cart->id)->first())->toBeNull();
});

it('should believe PayGlocal over the token when the two disagree', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(payGlocalCallbackClaims($cart));

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(payGlocalStatusResponse($cart, PayGlocalPaymentStatus::CUSTOMER_CANCELLED->value));

    $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    expect(Order::query()->where('cart_id', $cart->id)->first())->toBeNull();
});

// ============================================================================
// Success Page
// ============================================================================

it('should show the customer the order the callback placed', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(payGlocalCallbackClaims($cart));

    mockPayGlocalConfirmedStatus($cart);

    $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    $response = $this->get(route('payglocal.success', ['merchantTxnId' => 'PGL'.$cart->id.'TTEST']));

    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $response->assertSessionHas('order_id', Order::query()->where('cart_id', $cart->id)->first()->id);
});

it('should not be made to place an order by typing a reference', function () {
    $cart = $this->createCartWithItems('payglocal');

    $response = $this->get(route('payglocal.success', [
        'gid' => 'gl_o-test_gid',
        'merchantTxnId' => 'PGL'.$cart->id.'TTEST',
    ]));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    expect(Order::query()->where('cart_id', $cart->id)->first())->toBeNull();
});

// ============================================================================
// Webhook
// ============================================================================

it('should create the order from a webhook when the customer never came back', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(payGlocalCallbackClaims($cart));

    mockPayGlocalConfirmedStatus($cart);

    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    $response->assertOk();

    $response->assertJsonPath('status', 'order_created');

    expect(Order::query()->where('cart_id', $cart->id)->first())->not->toBeNull();
});

it('should not create a second order when the webhook arrives after the callback', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(payGlocalCallbackClaims($cart));

    mockPayGlocalConfirmedStatus($cart);

    $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    $response->assertOk();

    $response->assertJsonPath('status', 'order_already_exists');

    expect(Order::query()->where('cart_id', $cart->id)->count())->toBe(1);
});

it('should ignore a webhook whose token is not signed by PayGlocal', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(null);

    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'forged']);

    $response->assertOk();

    $response->assertJsonPath('status', 'transaction_not_found');

    expect(Order::query()->where('cart_id', $cart->id)->first())->toBeNull();
});

// ============================================================================
// Captured Payment Verification
// ============================================================================

it('should refuse to place the order when the cart no longer totals what was captured', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(payGlocalCallbackClaims($cart));

    mockPayGlocalConfirmedStatus($cart, [
        'Amount' => (string) ($cart->base_grand_total + 100),
    ]);

    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    $response->assertOk();

    $response->assertJsonPath('status', 'payment_not_confirmed');

    expect(Order::query()->where('cart_id', $cart->id)->first())->toBeNull();
});

it('should settle against the totals collectTotals recalculated, not the stale ones it was handed', function () {
    $cart = $this->createCartWithItems('payglocal');

    driftPayGlocalCartTotals($cart, 500);

    mockPayGlocalCallbackToken(payGlocalCallbackClaims($cart));

    mockPayGlocalConfirmedStatus($cart);

    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    $response->assertOk();

    $order = Order::query()->where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and((float) $order->base_grand_total)->toBe((float) $cart->base_grand_total);
});

it('should refuse to place the order when the cart currency no longer matches what was captured', function () {
    $cart = $this->createCartWithItems('payglocal');

    mockPayGlocalCallbackToken(payGlocalCallbackClaims($cart));

    mockPayGlocalConfirmedStatus($cart, ['txnCurrency' => 'EUR']);

    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    $response->assertOk();

    $response->assertJsonPath('status', 'payment_not_confirmed');

    expect(Order::query()->where('cart_id', $cart->id)->first())->toBeNull();
});
