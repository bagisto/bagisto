<?php

use Illuminate\Support\Facades\Http;
use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Models\CoreConfig;
use Webkul\PayGlocal\Enums\PayGlocalPaymentStatus;
use Webkul\PayGlocal\Enums\PayGlocalTransactionStatus;
use Webkul\PayGlocal\Helpers\Crypto;
use Webkul\PayGlocal\Models\PayGlocalTransaction;
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

    expect(PayGlocalTransaction::where('cart_id', $cart->id)->first())->toBeNull();
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

it('records the payment attempt before sending the customer to payglocal', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    Cart::shouldReceive('getCart')->andReturn($cart);

    $this->payGlocalMock->shouldReceive('initiatePayment')
        ->andReturn([
            'gid' => 'gl_o-test_gid',
            'statusUrl' => 'https://api.uat.payglocal.in/gl/v1/payments/gl_o-test_gid/status?x-gl-token=token',
            'redirectUrl' => 'https://api.uat.payglocal.in/gl/payflow-ui/?x-gl-token=token',
        ]);

    // Act
    $response = $this->get(route('payglocal.redirect'));

    // Assert
    $response->assertRedirect('https://api.uat.payglocal.in/gl/payflow-ui/?x-gl-token=token');

    $transaction = PayGlocalTransaction::where('cart_id', $cart->id)->first();

    expect($transaction)->not->toBeNull()
        ->and($transaction->gid)->toBe('gl_o-test_gid')
        ->and($transaction->status)->toBe(PayGlocalTransactionStatus::PENDING)
        ->and($transaction->currency)->toBe($cart->cart_currency_code)
        ->and((float) $transaction->amount)->toBe((float) $cart->grand_total);
});

it('marks the attempt failed when payglocal will not start the payment', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    Cart::shouldReceive('getCart')->andReturn($cart);

    $this->payGlocalMock->shouldReceive('initiatePayment')->andReturn(null);

    // Act
    $response = $this->get(route('payglocal.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    $transaction = PayGlocalTransaction::where('cart_id', $cart->id)->first();

    expect($transaction->status)->toBe(PayGlocalTransactionStatus::FAILED);
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

    $transaction = createTransaction($cart);

    mockSuccessfulStatus($this->payGlocalMock, $transaction);

    // Act
    $response = $this->get(route('payglocal.success', ['gid' => $transaction->gid]));

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $response->assertSessionHas('success');

    $response->assertSessionHas('order_id');

    // Verify order was created
    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    // Verify order transaction was created
    $orderTransaction = OrderTransaction::where('transaction_id', $transaction->gid)->first();

    // Verify what PayGlocal answered is kept against the transaction, for reconciliation later
    expect(json_decode($orderTransaction->data, true))->toEqual(capturedStatusResponse());

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

    // Verify the payment attempt was settled
    $transaction->refresh();

    expect($transaction->status)->toBe(PayGlocalTransactionStatus::COMPLETED)
        ->and($transaction->order_id)->toBe($order->id);
});

it('tells the customer a payment is pending rather than failed while it is still running', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    $transaction = createTransaction($cart);

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(['status' => PayGlocalPaymentStatus::INPROGRESS->value]);

    // Act
    $response = $this->get(route('payglocal.success', ['gid' => $transaction->gid]));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('warning');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('records a cancelled payment against the attempt', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    $transaction = createTransaction($cart);

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(['status' => PayGlocalPaymentStatus::CUSTOMER_CANCELLED->value]);

    // Act
    $response = $this->get(route('payglocal.success', ['gid' => $transaction->gid]));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    $transaction->refresh();

    expect($transaction->status)->toBe(PayGlocalTransactionStatus::CANCELLED);
});

it('acknowledges a webhook that refers to an unknown payment', function () {
    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['gid' => 'gl_o-unknown']);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'transaction_not_found');
});

it('creates the order from a webhook when the customer never came back', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    $transaction = createTransaction($cart);

    mockSuccessfulStatus($this->payGlocalMock, $transaction);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['gid' => $transaction->gid]);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'order_created');

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    $transaction->refresh();

    expect($transaction->status)->toBe(PayGlocalTransactionStatus::COMPLETED);
});

it('does not create a second order when the webhook arrives after the callback', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    $transaction = createTransaction($cart);

    mockSuccessfulStatus($this->payGlocalMock, $transaction);

    $this->postJson(route('payglocal.webhook'), ['gid' => $transaction->gid])->assertOk();

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['gid' => $transaction->gid]);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'order_already_exists');

    expect(Order::where('cart_id', $cart->id)->count())->toBe(1);
});

it('does not create an order from a webhook when the payment is not confirmed', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    $transaction = createTransaction($cart);

    $this->payGlocalMock->shouldReceive('getTransactionStatus')
        ->andReturn(['status' => PayGlocalPaymentStatus::ISSUER_DECLINE->value]);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['gid' => $transaction->gid]);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'payment_not_confirmed');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();

    $transaction->refresh();

    expect($transaction->status)->toBe(PayGlocalTransactionStatus::FAILED);
});

it('refuses to place the order when the cart no longer totals what was captured', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    $transaction = createTransaction($cart, ['amount' => $cart->grand_total + 100]);

    mockSuccessfulStatus($this->payGlocalMock, $transaction);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['gid' => $transaction->gid]);

    // Assert
    // Acknowledged, so that PayGlocal stops redelivering something retrying cannot fix.
    $response->assertOk();

    $response->assertJsonPath('status', 'order_creation_failed');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

it('refuses to place the order when the cart currency no longer matches what was captured', function () {
    // Arrange
    $cart = $this->createCartWithItems('payglocal');

    $transaction = createTransaction($cart, ['currency' => 'EUR']);

    mockSuccessfulStatus($this->payGlocalMock, $transaction);

    // Act
    $response = $this->postJson(route('payglocal.webhook'), ['gid' => $transaction->gid]);

    // Assert
    $response->assertOk();

    $response->assertJsonPath('status', 'order_creation_failed');

    expect(Order::where('cart_id', $cart->id)->first())->toBeNull();
});

/**
 * Create the payment attempt a callback or webhook would resolve to.
 */
function createTransaction($cart, array $overrides = []): PayGlocalTransaction
{
    return PayGlocalTransaction::create(array_merge([
        'cart_id' => $cart->id,
        'merchant_txn_id' => 'PGL'.$cart->id.'TTEST',
        'gid' => 'gl_o-test_gid',
        'status_url' => 'https://api.uat.payglocal.in/gl/v1/payments/gl_o-test_gid/status?x-gl-token=token',
        'amount' => $cart->grand_total,
        'currency' => $cart->cart_currency_code,
        'status' => PayGlocalTransactionStatus::PENDING->value,
    ], $overrides));
}

/**
 * The body PayGlocal's status API answers with for a captured payment, in the shape it really
 * sends. The status API is the only thing an order is ever built from, so a captured payment is
 * simulated by answering that call rather than by faking a callback or webhook payload.
 */
function capturedStatusResponse(): array
{
    return [
        'gid' => 'gl_a1c7fa4ddc487f1cf25uut0lTX2',
        'status' => PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value,
        'message' => 'Transaction is sent_for_capture',
        'timestamp' => '17/07/2026 14:20:41',
        'reasonCode' => 'GL-201-001',
        'data' => [
            'transactionCreationTime' => '17/07/2026 14:16:43',
            'gid' => 'gl_o-test_gid',
            'payment-method' => 'CARD',
            'Amount' => '338.95',
            'txnCurrency' => 'INR',
            'merchantTxnId' => 'PGL1TTEST',
            'CardBrand' => 'VISA',
            'CardType' => 'CREDIT',
            'processor' => 'payglocal',
            'authApprovalCode' => '831000',
            'detailedMessage' => 'Sent for capture successfully',
            'reasonCode' => 'GL-201-001',
            'status' => PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value,
        ],
        'errors' => null,
    ];
}

/**
 * Answer the status call for one particular attempt, so that a test proves the status URL that
 * was stored is the one asked about rather than any URL at all.
 */
function mockSuccessfulStatus($payGlocalMock, ?PayGlocalTransaction $transaction = null): void
{
    $expectation = $payGlocalMock->shouldReceive('getTransactionStatus');

    if ($transaction) {
        $expectation->with($transaction->status_url);
    }

    $expectation->andReturn(capturedStatusResponse());
}
