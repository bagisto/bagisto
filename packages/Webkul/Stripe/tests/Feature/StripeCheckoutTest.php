<?php

use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;
use Webkul\Stripe\Payment\Stripe;

beforeEach(function () {
    $this->setConfig([
        'sales.payment_methods.stripe.active' => '1',
        'sales.payment_methods.stripe.sandbox' => '1',
        'sales.payment_methods.stripe.api_test_key' => 'sk_test_fake_key',
        'sales.payment_methods.stripe.api_test_publishable_key' => 'pk_test_fake_key',
    ]);
});

// ============================================================================
// Redirect
// ============================================================================

it('should send the customer to Stripe to pay for their cart', function () {
    $cart = $this->createCartWithItems('stripe');

    $client = $this->fakeStripeApi([
        'POST /v1/checkout/sessions' => [200, [
            'id' => 'cs_test_redirect',
            'object' => 'checkout.session',
            'url' => 'https://checkout.stripe.com/c/pay/cs_test_redirect',
            'payment_status' => 'unpaid',
        ]],
    ]);

    $this->actingAs(Customer::query()->find($cart->customer_id), 'customer')
        ->get(route('stripe.standard.redirect'))
        ->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_redirect');

    expect($client->requestsTo('POST', '/v1/checkout/sessions')[0]['params']['metadata']['cart_id'])->toBe($cart->id);
});

it('should bring the customer back to the cart when Stripe cannot start the payment', function () {
    $cart = $this->createCartWithItems('stripe');

    $this->fakeStripeApi([
        'POST /v1/checkout/sessions' => [400, [
            'error' => [
                'type' => 'invalid_request_error',
                'message' => 'Invalid currency.',
            ],
        ]],
    ]);

    $this->actingAs(Customer::query()->find($cart->customer_id), 'customer')
        ->get(route('stripe.standard.redirect'))
        ->assertRedirect(route('shop.checkout.cart.index'))
        ->assertSessionHas('error', trans('stripe::app.response.payment-failed').': Invalid currency.');
});

// ============================================================================
// Payment Callbacks
// ============================================================================

it('should tell the customer when their cart changed after the payment started', function () {
    $cart = $this->createCartWithItems('stripe');

    $stripe = $this->mock(Stripe::class)->makePartial();

    $stripe->shouldReceive('retrieveCheckoutSession')->andReturn((object) [
        'id' => 'cs_test_changed',
        'payment_intent' => 'pi_test_changed',
        'payment_status' => 'paid',
        'metadata' => (object) [
            'cart_id' => $cart->id,
            'base_grand_total' => (string) ($cart->base_grand_total + 5),
        ],
    ]);

    $this->get(route('stripe.payment.success', ['session_id' => 'cs_test_changed']))
        ->assertRedirect(route('shop.checkout.cart.index'))
        ->assertSessionHas('error', trans('stripe::app.response.cart-changed'));

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse()
        ->and($cart->fresh()->is_active)->toBeTrue();
});
