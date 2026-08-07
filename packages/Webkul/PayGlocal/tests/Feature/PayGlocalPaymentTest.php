<?php

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

it('forwards a verified reference on to the success route', function () {
    // Arrange
    $cryptoMock = $this->mock(Crypto::class)->makePartial();

    $cryptoMock->shouldReceive('verify')->with('token')->andReturn(['gid' => 'gl_o-forwarded']);

    $this->app->instance(Crypto::class, $cryptoMock);

    // Act
    $response = $this->post(route('payglocal.callback'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertRedirect(route('payglocal.success', ['gid' => 'gl_o-forwarded']));
});

it('forwards no reference when the callback token cannot be verified', function () {
    // Arrange
    $cryptoMock = $this->mock(Crypto::class)->makePartial();

    $cryptoMock->shouldReceive('verify')->with('invalid_token')->andReturn(null);

    $this->app->instance(Crypto::class, $cryptoMock);

    // Act
    $response = $this->post(route('payglocal.callback'), ['x-gl-token' => 'invalid_token']);

    // Assert
    $response->assertRedirect(route('payglocal.success'));
});

it('forwards no reference when the callback carries no token', function () {
    // Act
    $response = $this->post(route('payglocal.callback'));

    // Assert
    $response->assertRedirect(route('payglocal.success'));
});

it('does not start a session when receiving the callback', function () {
    // Act
    $response = $this->post(route('payglocal.callback'));

    // Assert
    expect($response->headers->getCookies())->toBeEmpty();
});

it('redirects to cart when no payment reference is supplied', function () {
    // Act
    $response = $this->get(route('payglocal.success'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when the reference points at an unknown payment', function () {
    // Act
    $response = $this->get(route('payglocal.success', ['gid' => 'gl_o-unknown']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('successfully processes payglocal payment and creates order with invoice', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockSuccessfulStatus($this->payGlocalMock, $cart);

    // Act
    $response = $this->get(route('payglocal.success', ['gid' => 'gl_o-test_gid']));

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $response->assertSessionHas('success');

    $response->assertSessionHas('order_id');

    // Verify order was created
    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    // Verify order transaction was created, which is the only record kept of the payment
    $orderTransaction = OrderTransaction::where('transaction_id', 'gl_o-test_gid')->first();

    // Verify what PayGlocal answered is kept against the transaction, for reconciliation later
    expect(json_decode($orderTransaction->data, true))->toEqual(capturedStatusResponse($cart));

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->order_id)->toBe($order->id)
        ->and($orderTransaction->status)->toBe(PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value)
        // Recorded in the base currency, which is what the admin renders transactions in.
        ->and((float) $orderTransaction->amount)->toBe((float) $order->base_grand_total);

    // Verify invoice was created
    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull();

    // Verify cart was deactivated
    $cart->refresh();

    expect($cart->is_active)->toBe(0);
});

it('finds the cart from the reference it was given rather than the status body', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockSuccessfulStatus($this->payGlocalMock, $cart, ['merchantTxnId' => null]);

    // Act
    $response = $this->get(route('payglocal.success', [
        'gid' => 'gl_o-test_gid',
        'merchantTxnId' => 'PGL'.$cart->id.'TTEST',
    ]));

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    expect(Order::where('cart_id', $cart->id)->first())->not->toBeNull();
});

it('tells the customer a payment is pending rather than failed while it is still running', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(statusResponse($cart, PayGlocalPaymentStatus::INPROGRESS->value));

    // Act
    $response = $this->get(route('payglocal.success', ['gid' => 'gl_o-test_gid']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('warning');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('tells the customer their payment was cancelled', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(statusResponse($cart, PayGlocalPaymentStatus::CUSTOMER_CANCELLED->value));

    // Act
    $response = $this->get(route('payglocal.success', ['gid' => 'gl_o-test_gid']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('acknowledges a webhook that refers to an unknown payment', function () {
    // Arrange
    mockWebhookToken(['gid' => 'gl_o-unknown']);

    $this->payGlocalMock->shouldReceive('getTransactionStatus')->andReturn(null);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'transaction_not_found');
});

it('ignores a webhook whose token is not signed by payglocal', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockWebhookToken(null);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'forged']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'transaction_not_found');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('creates the order from a webhook when the customer never came back', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockWebhookToken(['gid' => 'gl_o-test_gid']);

    mockSuccessfulStatus($this->payGlocalMock, $cart);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'order_created');

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');
});

it('does not create a second order when the webhook arrives after the callback', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockWebhookToken(['gid' => 'gl_o-test_gid']);

    mockSuccessfulStatus($this->payGlocalMock, $cart);

    $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token'])->assertOk();

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'order_already_exists');

    expect(Order::where('cart_id', $cart->id)->count())->toBe(1);
});

it('does not create an order from a webhook when the payment is not confirmed', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockWebhookToken(['gid' => 'gl_o-test_gid']);

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(statusResponse($cart, PayGlocalPaymentStatus::ISSUER_DECLINE->value));

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'payment_not_confirmed');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('refuses to place the order when the cart no longer totals what was captured', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockWebhookToken(['gid' => 'gl_o-test_gid']);

    mockSuccessfulStatus($this->payGlocalMock, $cart, [
        'Amount' => (string) ($cart->grand_total + 100),
    ]);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    // Acknowledged, so that PayGlocal stops redelivering something retrying cannot fix.
    $response->assertOk();

    $response->assertJsonPath('status', 'order_creation_failed');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('refuses to place the order when the cart currency no longer matches what was captured', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    mockWebhookToken(['gid' => 'gl_o-test_gid']);

    mockSuccessfulStatus($this->payGlocalMock, $cart, ['txnCurrency' => 'EUR']);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['x-gl-token' => 'token']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'order_creation_failed');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

/**
 * The body PayGlocal's status API answers with, in the shape it really sends. That call is the
 * only thing an order is ever built from, so a payment is simulated by answering it rather than
 * by faking a callback or webhook payload.
 */
function statusResponse($cart, string $status, array $dataOverrides = []): array
{
    return [
        'gid' => 'gl_o-test_gid',
        'status' => $status,
        'message' => 'Transaction is '.strtolower($status),
        'timestamp' => '17/07/2026 14:20:41',
        'reasonCode' => 'GL-201-001',
        'data' => array_filter(array_merge([
            'transactionCreationTime' => '17/07/2026 14:16:43',
            'gid' => 'gl_o-test_gid',
            'payment-method' => 'CARD',
            'Amount' => (string) $cart->grand_total,
            'txnCurrency' => $cart->cart_currency_code,
            'merchantTxnId' => 'PGL'.$cart->id.'TTEST',
            'CardBrand' => 'VISA',
            'CardType' => 'CREDIT',
            'processor' => 'payglocal',
            'authApprovalCode' => '831000',
            'detailedMessage' => 'Sent for capture successfully',
            'reasonCode' => 'GL-201-001',
            'status' => $status,
        ], $dataOverrides), fn ($value) => $value !== null),
        'errors' => null,
    ];
}

/**
 * The status body for a captured payment.
 */
function capturedStatusResponse($cart, array $dataOverrides = []): array
{
    return statusResponse($cart, PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value, $dataOverrides);
}

/**
 * Answer the status call for a captured payment on the given cart.
 */
function mockSuccessfulStatus($payGlocalMock, $cart, array $dataOverrides = []): void
{
    $payGlocalMock->shouldReceive('getTransactionStatus')
        ->with('gl_o-test_gid')
        ->andReturn(capturedStatusResponse($cart, $dataOverrides));
}

/**
 * Stand in for the signed token PayGlocal posts to the webhook. Passing null models a token
 * that does not verify, which must be refused.
 */
function mockWebhookToken(?array $claims): void
{
    $cryptoMock = test()->mock(Crypto::class)->makePartial();

    $cryptoMock->shouldReceive('verify')->andReturn($claims);

    app()->instance(Crypto::class, $cryptoMock);
}
