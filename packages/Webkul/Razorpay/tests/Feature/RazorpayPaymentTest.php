<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Razorpay\Enums\PaymentStatus;
use Webkul\Razorpay\Models\RazorpayTransaction;
use Webkul\Razorpay\Payment\RazorpayPayment;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

beforeEach(function () {
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.active',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_id',
        'value' => 'rzp_test_fake_key',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'fake_test_secret',
    ]);
});

it('redirects back when razorpay credentials are invalid', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_id',
        'value' => '',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => '',
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
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = CartModel::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'shipping_method'     => 'free_free',
        'base_currency_code'  => 'INR',
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem = CartItem::factory()->create([
        'cart_id'             => $cart->id,
        'product_id'          => $product->id,
        'sku'                 => $product->sku,
        'quantity'            => $additional['quantity'],
        'name'                => $product->name,
        'price'               => $convertedPrice = core()->convertPrice($price = $product->price),
        'price_incl_tax'      => $convertedPrice,
        'base_price'          => $price,
        'base_price_incl_tax' => $price,
        'total'               => $total = $convertedPrice * $additional['quantity'],
        'total_incl_tax'      => $total,
        'base_total'          => $price * $additional['quantity'],
        'weight'              => $product->weight ?? 0,
        'total_weight'        => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight'   => ($product->weight ?? 0) * $additional['quantity'],
        'type'                => $product->type,
        'additional'          => $additional,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => 'razorpay',
        'method_title' => 'Razorpay',
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'cart_id'            => $cart->id,
    ]);

    Cart::setCart($cart);

    Cart::collectTotals();

    // Mock RazorpayPayment class to avoid actual API calls
    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('createOrder')->andReturn([
        'id'       => 'order_test123',
        'amount'   => 33415,
        'currency' => 'INR',
        'receipt'  => 'receipt_'.$cart->id,
    ]);

    $mockRazorpay->shouldReceive('preparePaymentData')->andReturn([
        'key'      => 'test_key',
        'amount'   => 33415,
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

    // Verify transaction was created
    $transaction = RazorpayTransaction::where('cart_id', $cart->id)->first();

    expect($transaction)->not->toBeNull()
        ->and($transaction->razorpay_invoice_status)->toBe(PaymentStatus::AWAITING_PAYMENT)
        ->and($transaction->razorpay_order_id)->not->toBeEmpty();
});

it('successfully processes razorpay payment and creates order with invoice', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = CartModel::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'shipping_method'     => 'free_free',
        'base_currency_code'  => 'INR',
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem = CartItem::factory()->create([
        'cart_id'             => $cart->id,
        'product_id'          => $product->id,
        'sku'                 => $product->sku,
        'quantity'            => $additional['quantity'],
        'name'                => $product->name,
        'price'               => $convertedPrice = core()->convertPrice($price = $product->price),
        'price_incl_tax'      => $convertedPrice,
        'base_price'          => $price,
        'base_price_incl_tax' => $price,
        'total'               => $total = $convertedPrice * $additional['quantity'],
        'total_incl_tax'      => $total,
        'base_total'          => $price * $additional['quantity'],
        'weight'              => $product->weight ?? 0,
        'total_weight'        => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight'   => ($product->weight ?? 0) * $additional['quantity'],
        'type'                => $product->type,
        'additional'          => $additional,
    ]);

    $cartBillingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $cartShippingAddress = CartAddress::factory()->create([
        'cart_id'      => $cart->id,
        'customer_id'  => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $cartPayment = CartPayment::factory()->create([
        'cart_id'      => $cart->id,
        'method'       => 'razorpay',
        'method_title' => 'Razorpay',
    ]);

    $cartShippingRate = CartShippingRate::factory()->create([
        'carrier'            => 'free',
        'carrier_title'      => 'Free shipping',
        'method'             => 'free_free',
        'method_title'       => 'Free Shipping',
        'method_description' => 'Free Shipping',
        'cart_address_id'    => $cartShippingAddress->id,
        'cart_id'            => $cart->id,
    ]);

    Cart::setCart($cart);

    Cart::collectTotals();

    // Create the initial transaction
    $transaction = RazorpayTransaction::create([
        'cart_id'                 => $cart->id,
        'razorpay_order_id'       => 'order_test123',
        'razorpay_invoice_status' => PaymentStatus::AWAITING_PAYMENT,
    ]);

    // Mock the RazorpayPayment methods
    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('verifySignature')->andReturn(true);

    $mockRazorpay->shouldReceive('fetchPayment')->andReturn((object) ['status' => 'captured']);

    $this->app->instance(RazorpayPayment::class, $mockRazorpay);

    // Act
    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_payment_id' => 'pay_test123',
        'razorpay_order_id'   => 'order_test123',
        'razorpay_signature'  => 'test_signature',
    ]));

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    // Verify order was created
    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing')
        ->and($order->customer_id)->toBe($customer->id);

    // Verify invoice was created
    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull()
        ->and($invoice->state)->toBe('paid');

    // Verify transaction was updated
    $transaction->refresh();

    expect($transaction->razorpay_payment_id)->toBe('pay_test123')
        ->and($transaction->razorpay_invoice_status)->toBe(PaymentStatus::CAPTURED);

    // Verify order transaction was created
    $orderTransaction = OrderTransaction::where('order_id', $order->id)->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->transaction_id)->toBe('pay_test123')
        ->and($orderTransaction->status)->toBe('captured')
        ->and($orderTransaction->type)->toBe('razorpay');
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

    $transaction = RazorpayTransaction::create([
        'cart_id'                 => $cart->id,
        'razorpay_order_id'       => 'order_fail_123',
        'razorpay_receipt'        => 'receipt_'.$cart->id,
        'razorpay_invoice_status' => PaymentStatus::AWAITING_PAYMENT,
    ]);

    // Act
    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_order_id' => 'order_fail_123',
        'error'             => 'payment_failed',
    ]));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    // Verify transaction status was updated to payment error
    $transaction->refresh();

    expect($transaction->razorpay_invoice_status)->toBe(PaymentStatus::PAYMENT_ERROR);
});

it('redirects to cart when signature verification fails', function () {
    // Arrange
    $cart = CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    $transaction = RazorpayTransaction::create([
        'cart_id'                 => $cart->id,
        'razorpay_order_id'       => 'order_test_456',
        'razorpay_receipt'        => 'receipt_'.$cart->id,
        'razorpay_invoice_status' => PaymentStatus::AWAITING_PAYMENT,
    ]);

    // Mock invalid signature
    $mockRazorpay = $this->mock(RazorpayPayment::class)->makePartial();

    $mockRazorpay->shouldReceive('verifySignature')->andReturn(false);

    $this->app->instance(RazorpayPayment::class, $mockRazorpay);

    // Act
    $response = $this->get(route('razorpay.payment.success', [
        'razorpay_payment_id' => 'pay_test_456',
        'razorpay_order_id'   => 'order_test_456',
        'razorpay_signature'  => 'invalid_signature',
    ]));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});
