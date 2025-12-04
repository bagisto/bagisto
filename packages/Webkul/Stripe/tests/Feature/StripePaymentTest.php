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
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;
use Webkul\Stripe\Enums\StripeTransactionStatus;
use Webkul\Stripe\Models\StripeTransaction;
use Webkul\Stripe\Payment\Stripe;

beforeEach(function () {
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.stripe.active',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.stripe.sandbox',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.stripe.api_test_key',
        'value' => 'sk_test_fake_key',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value' => 'pk_test_fake_key',
    ]);
});

it('redirects to cart when stripe credentials are invalid', function () {
    // Arrange - Override with empty credentials
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.stripe.api_test_key',
        'value' => '',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value' => '',
    ]);

    // Act
    $response = $this->get(route('stripe.standard.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when cart is not found', function () {
    // Arrange
    Cart::shouldReceive('getCart')->andReturn(null);

    // Act
    $response = $this->get(route('stripe.standard.redirect'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when session id is missing on success callback', function () {
    // Act
    $response = $this->get(route('stripe.payment.success'));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when stripe transaction is not found', function () {
    // Act
    $response = $this->get(route('stripe.payment.success', ['session_id' => 'invalid_session']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('updates transaction status to cancelled on cancel callback', function () {
    // Arrange
    $transaction = StripeTransaction::create([
        'cart_id'    => 1,
        'session_id' => 'cs_test_cancel_123',
        'amount'     => 100.00,
        'status'     => StripeTransactionStatus::PENDING,
    ]);

    // Act
    $response = $this->get(route('stripe.payment.cancel', ['session_id' => 'cs_test_cancel_123']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    $transaction->refresh();

    expect($transaction->status)->toBe(StripeTransactionStatus::CANCELLED);
});

it('shows error message on payment cancellation', function () {
    // Act
    $response = $this->get(route('stripe.payment.cancel', ['session_id' => 'cs_test_123']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('redirects to cart when cart is already processed', function () {
    // Arrange
    $product = (new ProductFaker)
        ->getSimpleProductFactory()
        ->create();

    $customer = Customer::factory()->create();

    $cart = CartModel::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'base_grand_total'    => 100.00,
        'grand_total'         => 100.00,
    ]);

    $transaction = StripeTransaction::create([
        'cart_id'    => $cart->id,
        'session_id' => 'cs_test_already_processed',
        'amount'     => 100.00,
        'status'     => StripeTransactionStatus::PENDING,
    ]);

    $mockSession = (object) [
        'id'             => 'cs_test_already_processed',
        'payment_intent' => 'pi_test_123',
        'payment_status' => 'paid',
        'status'         => 'complete',
    ];

    $stripeMock = $this->mock(Stripe::class)->makePartial();

    $stripeMock->shouldReceive('retrieveCheckoutSession')
        ->with('cs_test_already_processed')
        ->andReturn($mockSession);

    $this->app->instance(Stripe::class, $stripeMock);

    // Act
    $response = $this->get(route('stripe.payment.success', ['session_id' => 'cs_test_already_processed']));

    // Assert
    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');

    expect($response->getSession()->get('error'))->toContain('cart');
});

it('successfully processes stripe payment and creates order with invoice', function () {
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
        'method'       => 'stripe',
        'method_title' => 'Stripe',
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

    $transaction = StripeTransaction::create([
        'cart_id'    => $cart->id,
        'session_id' => 'cs_test_success_123',
        'amount'     => $cart->base_grand_total,
        'status'     => StripeTransactionStatus::PENDING,
    ]);

    Cart::setCart($cart);

    Cart::collectTotals();

    $mockSession = (object) [
        'id'             => 'cs_test_success_123',
        'payment_intent' => 'pi_test_123',
        'payment_status' => 'paid',
        'status'         => 'complete',
    ];

    $stripeMock = $this->mock(Stripe::class)->makePartial();

    $stripeMock->shouldReceive('retrieveCheckoutSession')
        ->with('cs_test_success_123')
        ->andReturn($mockSession);

    $this->app->instance(Stripe::class, $stripeMock);

    // Act
    $response = $this->get(route('stripe.payment.success', ['session_id' => 'cs_test_success_123']));

    // Assert
    $response->assertRedirect(route('shop.checkout.onepage.success'));

    $response->assertSessionHas('success');

    $response->assertSessionHas('order_id');

    // Verify order was created
    $order = Order::where('customer_id', $customer->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe('processing');

    // Verify transaction was updated
    $transaction->refresh();

    expect($transaction->status)->toBe(StripeTransactionStatus::COMPLETED)
        ->and($transaction->payment_intent_id)->toBe('pi_test_123')
        ->and($transaction->order_id)->toBe($order->id);

    // Verify order transaction was created
    $orderTransaction = OrderTransaction::where('transaction_id', 'pi_test_123')->first();

    expect($orderTransaction)->not->toBeNull()
        ->and($orderTransaction->order_id)->toBe($order->id)
        ->and($orderTransaction->status)->toBe(StripeTransactionStatus::COMPLETED->value);

    // Verify invoice was created
    $invoice = Invoice::where('order_id', $order->id)->first();

    expect($invoice)->not->toBeNull();

    // Verify cart was deactivated
    $cart->refresh();

    expect($cart->is_active)->toBe(0);
});
