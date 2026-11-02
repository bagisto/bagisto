<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\PhonePe\Payment\PhonePe;

beforeEach(function () {
    $this->setConfig([
        'sales.payment_methods.phonepe.active' => '1',
        'sales.payment_methods.phonepe.sandbox' => '1',
        'sales.payment_methods.phonepe.merchant_id' => 'test_merchant',
        'sales.payment_methods.phonepe.client_id' => 'test_client_id',
        'sales.payment_methods.phonepe.client_secret' => 'test_client_secret',
    ]);
});

// ============================================================================
// Redirect
// ============================================================================

it('should redirect to the cart when the PhonePe credentials are missing', function () {
    $this->setConfig([
        'sales.payment_methods.phonepe.client_id' => '',
        'sales.payment_methods.phonepe.client_secret' => '',
    ]);

    $response = $this->get(route('phonepe.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should redirect to the cart when the cart is not found', function () {
    Cart::shouldReceive('getCart')->andReturn(null);

    $response = $this->get(route('phonepe.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should send a guest who has to sign in back to checkout instead of starting a PhonePe payment', function () {
    $this->setConfig('sales.checkout.shopping_cart.allow_guest_checkout', '0');

    $this->createCartWithItems('phonepe', ['base_currency_code' => 'INR']);

    $this->mock(PhonePe::class)->makePartial()->shouldNotReceive('initiatePayment');

    $response = $this->get(route('phonepe.redirect'));

    $response->assertRedirect(route('shop.checkout.onepage.index'));
});

it('should refuse a cart in a currency PhonePe does not settle', function () {
    $this->createCartWithItems('phonepe', ['base_currency_code' => 'USD']);

    $response = $this->get(route('phonepe.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});

it('should send the shopper to PhonePe once the payment is started', function () {
    $this->createCartWithItems('phonepe', ['base_currency_code' => 'INR']);

    $this->mock(PhonePe::class)->makePartial()
        ->shouldReceive('initiatePayment')
        ->once()
        ->andReturn('https://phonepe.test/pay/order-1');

    $response = $this->get(route('phonepe.redirect'));

    $response->assertRedirect('https://phonepe.test/pay/order-1');
});

it('should redirect to the cart when PhonePe will not start the payment', function () {
    $this->createCartWithItems('phonepe', ['base_currency_code' => 'INR']);

    $this->mock(PhonePe::class)->makePartial()
        ->shouldReceive('initiatePayment')
        ->andReturn(null);

    $response = $this->get(route('phonepe.redirect'));

    $response->assertRedirect(route('shop.checkout.cart.index'));

    $response->assertSessionHas('error');
});
