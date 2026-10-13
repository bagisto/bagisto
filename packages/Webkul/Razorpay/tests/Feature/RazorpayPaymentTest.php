<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Core\Models\CoreConfig;
use Webkul\Razorpay\Payment\RazorpayPayment;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

/**
 * A Razorpay API double that is its own order and payment resource: creating an order records the
 * amount it was charged for, and fetching the payment reports a capture of exactly that amount.
 */
function razorpayApiStub(string $orderId, string $currency = 'INR'): object
{
    return new class($orderId, $currency)
    {
        /**
         * The subunit amount the order was created for.
         */
        public ?int $orderAmount = null;

        /**
         * The order resource, which this double serves itself.
         */
        public object $order;

        /**
         * The payment resource, which this double serves itself.
         */
        public object $payment;

        /**
         * Point both resources at this double.
         */
        public function __construct(private string $orderId, private string $currency)
        {
            $this->order = $this;

            $this->payment = $this;
        }

        /**
         * Record the amount an order is created for, as the order resource does.
         */
        public function create(array $data): array
        {
            $this->orderAmount = $data['amount'];

            return [
                'id' => $this->orderId,
                'amount' => $data['amount'],
                'currency' => $data['currency'],
            ];
        }

        /**
         * Report a captured payment for the amount the order was created for.
         */
        public function fetch(string $paymentId): object
        {
            return (object) [
                'id' => $paymentId,
                'order_id' => $this->orderId,
                'amount' => $this->orderAmount,
                'currency' => $this->currency,
                'status' => 'captured',
            ];
        }
    };
}

beforeEach(function () {
    CoreConfig::where('code', 'like', 'sales.payment_methods.razorpay.%')->delete();

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_id',
        'value' => 'rzp_test_fake_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'fake_test_secret',
        'channel_code' => 'default',
    ]);
});

it('redirects back when razorpay credentials are invalid', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_id',
        'value' => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => '',
        'channel_code' => 'default',
    ]);

    // Act
    $response = $this->get(route('razorpay.payment.redirect'));

    // Assert
    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('redirects back when cart is not found', function () {
    // Arrange
    Cart::shouldReceive('getCart')->andReturn(null);

    // Act
    $response = $this->get(route('razorpay.payment.redirect'));

    // Assert
    $response->assertRedirect();

    $response->assertSessionHas('error');
});

it('creates razorpay order and returns drop-in UI view', function () {
    // Arrange
    $cart = $this->createCartWithItems('razorpay', ['base_currency_code' => 'INR']);

    // Mock RazorpayPayment class to avoid actual API calls
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

    // Act
    $response = $this->get(route('razorpay.payment.redirect'));

    // Assert
    $response->assertOk();

    $response->assertViewIs('razorpay::drop-in-ui');

    $response->assertViewHas('payment');
});

it('successfully processes razorpay payment and creates order with invoice', function () {
    // Arrange
    $cart = $this->createCartWithItems('razorpay', ['base_currency_code' => 'INR']);

    // Mock the RazorpayPayment methods
    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('verifySignature')->andReturn(true);

    $mockRazorpay->shouldReceive('fetchPayment')->andReturn([
        'order_id' => 'order_test123',
        'amount' => (int) round($cart->base_grand_total * 100),
        'currency' => 'INR',
        'status' => 'captured',
    ]);

    $this->app->instance(RazorpayPayment::class, $mockRazorpay);

    // Act
    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_payment_id' => 'pay_test123',
        'razorpay_order_id' => 'order_test123',
        'razorpay_signature' => 'test_signature',
    ]));

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
        ->and($orderTransaction->transaction_id)->toBe('pay_test123')
        ->and($orderTransaction->status)->toBe('captured')
        ->and($orderTransaction->type)->toBe('razorpay');
});

it('handles payment failure gracefully', function () {
    // Act
    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_order_id' => 'order_fail_123',
        'error' => 'payment_failed',
    ]));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when signature verification fails', function () {
    // Arrange
    $cart = CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    // Mock invalid signature
    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('verifySignature')->andReturn(false);

    $this->app->instance(RazorpayPayment::class, $mockRazorpay);

    // Act
    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_payment_id' => 'pay_test_456',
        'razorpay_order_id' => 'order_test_456',
        'razorpay_signature' => 'invalid_signature',
    ]));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

// ============================================================================
// Order Amount Rounding
// ============================================================================

it('places the order when the payment matches the amount the razorpay order was created for', function (float $price) {
    $cart = $this->createCartWithItems('razorpay', ['base_currency_code' => 'INR'], [
        'price' => ['float_value' => $price],
    ]);

    $razorpayApi = razorpayApiStub('order_round_1');

    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('getApi')->andReturn($razorpayApi);

    $this->app->instance(RazorpayPayment::class, $mockRazorpay);

    $this->get(route('razorpay.payment.redirect'))->assertOk();

    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_payment_id' => 'pay_round_1',
        'razorpay_order_id' => 'order_round_1',
        'razorpay_signature' => hash_hmac('sha256', 'order_round_1|pay_round_1', 'fake_test_secret'),
    ]));

    $response->assertRedirect(route('shop.checkout.onepage.success'));

    expect(Order::where('cart_id', $cart->id)->exists())->toBeTrue()
        ->and($razorpayApi->orderAmount)->toBe((int) round($price * 100));
})->with([19.99, 156.45, 1169.10, 250.00]);
