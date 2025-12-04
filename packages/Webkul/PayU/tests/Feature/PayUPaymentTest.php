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
use Webkul\PayU\Enums\TransactionStatus;
use Webkul\PayU\Models\PayUTransaction;
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

it('creates payu transaction and returns redirect view', function () {
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
        'method'       => 'payu',
        'method_title' => 'PayU',
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

    // Act
    $response = $this->get(route('payu.redirect'));

    // Assert
    $response->assertOk();

    $response->assertViewIs('payu::checkout.redirect');

    $response->assertViewHas('paymentUrl');
    $response->assertViewHas('paymentData');

    // Verify transaction was created
    $transaction = PayUTransaction::where('cart_id', $cart->id)->first();

    expect($transaction)->not->toBeNull()
        ->and($transaction->status)->toBe(TransactionStatus::PENDING)
        ->and($transaction->transaction_id)->not->toBeEmpty();
});

it('successfully processes payu payment and creates order with invoice', function () {
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
        'method'       => 'payu',
        'method_title' => 'PayU',
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
    $txnid = 'PAYU_TEST123';

    $transaction = PayUTransaction::create([
        'transaction_id' => $txnid,
        'cart_id'        => $cart->id,
        'amount'         => round($cart->base_grand_total, 2),
        'status'         => TransactionStatus::PENDING->value,
    ]);

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
        'amount'      => $transaction->amount,
        'productinfo' => 'Order #'.$cart->id,
        'firstname'   => $customer->first_name,
        'email'       => $customer->email,
        'hash'        => 'valid_hash_value',
    ];

    // Act
    $response = $this->post(route('payu.success'), $paymentData);

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

    expect($transaction->order_id)->toBe($order->id)
        ->and($transaction->status)->toBe(TransactionStatus::SUCCESS);

    // Verify order transaction was created
    $orderTransaction = OrderTransaction::where('order_id', $order->id)->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->transaction_id)->toBe($txnid)
        ->and($orderTransaction->status)->toBe(TransactionStatus::SUCCESS->value)
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

    $transaction = PayUTransaction::create([
        'transaction_id' => $txnid,
        'cart_id'        => $cart->id,
        'amount'         => 100.00,
        'status'         => TransactionStatus::PENDING->value,
    ]);

    // Act
    $response = $this->post(route('payu.failure'), [
        'txnid'  => $txnid,
        'status' => 'failure',
        'error'  => 'Payment declined',
    ]);

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    // Verify transaction status was updated to failed
    $transaction->refresh();

    expect($transaction->status)->toBe(TransactionStatus::FAILED);
});

it('redirects to cart when hash verification fails', function () {
    // Arrange
    $cart = CartModel::factory()->create([
        'base_grand_total' => 100.00,
    ]);

    $txnid = 'PAYU_INVALID_HASH';

    $transaction = PayUTransaction::create([
        'transaction_id' => $txnid,
        'cart_id'        => $cart->id,
        'amount'         => 100.00,
        'status'         => TransactionStatus::PENDING->value,
    ]);

    // Mock invalid hash verification
    $mockPayU = $this->mock(PayUPayment::class)->makePartial();

    $mockPayU->shouldReceive('verifyHash')->andReturn(false);

    $this->app->instance(PayUPayment::class, $mockPayU);

    // Act
    $response = $this->post(route('payu.success'), [
        'txnid'  => $txnid,
        'status' => 'success',
        'hash'   => 'invalid_hash',
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

    $transaction = PayUTransaction::create([
        'transaction_id' => $txnid,
        'cart_id'        => $cart->id,
        'amount'         => 100.00,
        'status'         => TransactionStatus::PENDING->value,
    ]);

    // Act
    $response = $this->post(route('payu.cancel'), [
        'txnid'  => $txnid,
        'status' => 'userCancelled',
    ]);

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('warning');

    // Verify transaction status was updated to cancelled
    $transaction->refresh();

    expect($transaction->status)->toBe(TransactionStatus::CANCELLED);
});
