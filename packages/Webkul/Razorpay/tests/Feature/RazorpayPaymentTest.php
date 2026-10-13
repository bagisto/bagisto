<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Core\Models\CoreConfig;
use Webkul\Razorpay\Payment\RazorpayPayment;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

beforeEach(function () {
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

it('places the order when the payment matches the amount the razorpay order was created for', function (float $price) {
    // Arrange
    $cart = $this->createCartWithItems('razorpay', ['base_currency_code' => 'INR'], [
        'price' => ['float_value' => $price],
    ]);

    $razorpayApi = new class
    {
        public ?int $orderAmount = null;

        public object $order;

        public object $payment;

        public function __construct()
        {
            $this->order = new class($this)
            {
                public function __construct(private object $api) {}

                public function create(array $data): array
                {
                    $this->api->orderAmount = $data['amount'];

                    return ['id' => 'order_round_1', 'amount' => $data['amount'], 'currency' => $data['currency']];
                }
            };

            $this->payment = new class($this)
            {
                public function __construct(private object $api) {}

                public function fetch(string $id): object
                {
                    return (object) [
                        'id' => $id,
                        'order_id' => 'order_round_1',
                        'amount' => $this->api->orderAmount,
                        'currency' => 'INR',
                        'status' => 'captured',
                    ];
                }
            };
        }
    };

    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('getApi')->andReturn($razorpayApi);

    $this->app->instance(RazorpayPayment::class, $mockRazorpay);

    // Act
    $this->get(route('razorpay.payment.redirect'))->assertOk();

    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_payment_id' => 'pay_round_1',
        'razorpay_order_id' => 'order_round_1',
        'razorpay_signature' => hash_hmac('sha256', 'order_round_1|pay_round_1', 'fake_test_secret'),
    ]));

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    expect(Order::where('cart_id', $cart->id)->exists())->toBeTrue()
        ->and($razorpayApi->orderAmount)->toBe((int) round($price * 100));
})->with([19.99, 156.45, 1169.10, 250.00]);
