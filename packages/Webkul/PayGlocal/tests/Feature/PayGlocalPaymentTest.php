<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Models\CoreConfig;
use Webkul\PayGlocal\Enums\PayGlocalPaymentStatus;
use Webkul\PayGlocal\Helpers\Crypto;
use Webkul\PayGlocal\Payment\PayGlocal;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

beforeEach(function () {
    Http::preventStrayRequests();

    foreach ([
        'active' => '1',
        'sandbox' => '1',
        'merchant_id' => 'test_merchant',
        'public_key_id' => 'test_public_kid',
        'private_key_id' => 'test_private_kid',
        'payglocal_public_key' => 'fake_public_key',
        'merchant_private_key' => 'fake_private_key',
        'accepted_currencies' => 'USD,INR',
    ] as $field => $value) {
        CoreConfig::factory()->create([
            'code' => 'sales.payment_methods.payglocal.'.$field,
            'value' => $value,
            'channel_code' => 'default',
        ]);
    }

    $this->payGlocalMock = $this->mock(PayGlocal::class)->makePartial();

    $this->payGlocalMock->shouldReceive('hasUsableKeys')->andReturn(true);

    $this->app->instance(PayGlocal::class, $this->payGlocalMock);
});

it('redirects to cart when the configured keys cannot be used', function () {
    // Arrange
    $payGlocalMock = $this->mock(PayGlocal::class)->makePartial();

    $payGlocalMock->shouldReceive('hasUsableKeys')->andReturn(false);

    $this->app->instance(PayGlocal::class, $payGlocalMock);

    // Act
    $response = $this->get(route('payglocal.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when the cart currency is not accepted', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    Cart::shouldReceive('getCart')->andReturn($cart);

    $this->payGlocalMock->shouldReceive('isCurrencySupported')->andReturn(false);

    // Act
    $response = $this->get(route('payglocal.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when cart is not found', function () {
    // Arrange
    Cart::shouldReceive('getCart')->andReturn(null);

    // Act
    $response = $this->get(route('payglocal.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('sends the customer to payglocal without recording anything of its own', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    Cart::shouldReceive('getCart')->andReturn($cart);

    $this->payGlocalMock->shouldReceive('initiatePayment')
        ->andReturn([
            'gid' => 'gl_o-test_gid',
            'redirectUrl' => 'https://api.uat.payglocal.in/gl/payflow-ui/?x-gl-token=token',
        ]);

    // Act
    $response = $this->get(route('payglocal.redirect'));

    // Assert
    $response->assertRedirect('https://api.uat.payglocal.in/gl/payflow-ui/?x-gl-token=token');
});

it('reports a failure when payglocal will not start the payment', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    Cart::shouldReceive('getCart')->andReturn($cart);

    $this->payGlocalMock->shouldReceive('initiatePayment')->andReturn(null);

    // Act
    $response = $this->get(route('payglocal.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('settles the payment and creates the order with an invoice from the callback', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(callbackClaims($cart));

    mockConfirmedStatus($this->payGlocalMock, $cart);

    // Act
    $response = $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertRedirect(route('payglocal.success', [
        'gid' => 'gl_o-test_gid',
        'merchantTxnId' => 'PGL'.$cart->id.'TTEST',
    ]));

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    // The only record kept of the payment is the core order transaction
    $orderTransaction = OrderTransaction::where('transaction_id', 'gl_o-test_gid')->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->order_id)->toBe($order->id)
        ->and($orderTransaction->status)->toBe(PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value)
        // Recorded in the base currency, which is what the admin renders transactions in.
        ->and((float) $orderTransaction->amount)->toBe((float) $order->base_grand_total);

    expect(Invoice::where('order_id', $order->id)->first())->not->toBeNull();

    $cart->refresh();

    expect($cart->is_active)->toBe(0);
});

it('does not start a session when receiving the callback', function () {
    // Act
    $response = $this->post(route('payglocal.callback'));

    // Assert
    expect($response->headers->getCookies())->toBeEmpty();
});

it('places no order when the callback token is not signed by payglocal', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(null);

    // Act
    $this->post(route('payglocal.callback'), ['x-gl-token' => 'forged']);

    // Assert
    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('places no order when payglocal does not confirm the payment', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(callbackClaims($cart));

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(statusResponse($cart, PayGlocalPaymentStatus::ISSUER_DECLINE->value));

    // Act
    $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    // Assert
    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('believes payglocal over the token when the two disagree', function () {
    // Arrange
    // The token says captured, the gateway says declined. The gateway wins.
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(callbackClaims($cart));

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(statusResponse($cart, PayGlocalPaymentStatus::CUSTOMER_CANCELLED->value));

    // Act
    $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    // Assert
    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('shows the customer the order the callback placed', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(callbackClaims($cart));

    mockConfirmedStatus($this->payGlocalMock, $cart);

    $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    // Act
    $response = $this->get(route('payglocal.success', ['merchantTxnId' => 'PGL'.$cart->id.'TTEST']));

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $response->assertSessionHas('order_id', Order::where('cart_id', $cart->id)->first()->id);
});

it('cannot be made to place an order by typing a reference', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    // Act
    $response = $this->get(route('payglocal.success', [
        'gid' => 'gl_o-test_gid',
        'merchantTxnId' => 'PGL'.$cart->id.'TTEST',
    ]));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('creates the order from a webhook when the customer never came back', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(callbackClaims($cart));

    mockConfirmedStatus($this->payGlocalMock, $cart);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'order_created');

    expect(Order::where('cart_id', $cart->id)->first())->not->toBeNull();
});

it('does not create a second order when the webhook arrives after the callback', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(callbackClaims($cart));

    mockConfirmedStatus($this->payGlocalMock, $cart);

    $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'order_already_exists');

    expect(Order::where('cart_id', $cart->id)->count())->toBe(1);
});

it('ignores a webhook whose token is not signed by payglocal', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(null);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'forged']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'transaction_not_found');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('refuses to place the order when the cart no longer totals what was captured', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(callbackClaims($cart));

    mockConfirmedStatus($this->payGlocalMock, $cart, [
        'Amount' => (string) ($cart->base_grand_total + 100),
    ]);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    // Acknowledged, so that PayGlocal stops redelivering something retrying cannot fix.
    $response->assertOk();

    $response->assertJsonPath('status', 'payment_not_confirmed');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('settles against the totals collect totals recalculated, not the stale ones it was handed', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    /**
     * Drift the stored totals away from what the items add up to. collectTotals() recomputes
     * them from the items, so the figures on the cart instance read back before it runs are
     * the drifted ones, and the figures it leaves behind are the true ones.
     */
    DB::table('cart')->where('id', $cart->id)->update([
        'grand_total' => $cart->grand_total + 500,
        'base_grand_total' => $cart->base_grand_total + 500,
        'sub_total' => $cart->sub_total + 500,
        'base_sub_total' => $cart->base_sub_total + 500,
    ]);

    mockCallbackToken(callbackClaims($cart));

    mockConfirmedStatus($this->payGlocalMock, $cart);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertOk();

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull();

    expect((float) $order->base_grand_total)->toBe((float) $cart->base_grand_total);
});

it('refuses to place the order when the cart currency no longer matches what was captured', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockCallbackToken(callbackClaims($cart));

    mockConfirmedStatus($this->payGlocalMock, $cart, ['txnCurrency' => 'EUR']);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'payment_not_confirmed');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

/**
 * The claims PayGlocal signs into the `x-gl-token` it posts back, in the shape it really sends:
 * the reference, the outcome, and the status url to confirm the outcome against.
 */
function callbackClaims($cart): array
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
function statusResponse($cart, string $status, array $dataOverrides = []): array
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
function mockConfirmedStatus($payGlocalMock, $cart, array $dataOverrides = []): void
{
    $payGlocalMock->shouldReceive('getTransactionStatus')
        ->with('https://api.uat.pygcl.com/gl/v1/payments/gl_o-test_gid/status?x-gl-token=token')
        ->andReturn(statusResponse($cart, PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value, $dataOverrides));
}

/**
 * Stand in for the signed token PayGlocal posts. Passing null models a token that does not
 * verify, which must never settle anything.
 */
function mockCallbackToken(?array $claims): void
{
    $cryptoMock = test()->mock(Crypto::class)->makePartial();

    $cryptoMock->shouldReceive('verify')->andReturn($claims);

    app()->instance(Crypto::class, $cryptoMock);
}
