<?php

use Illuminate\Support\Facades\Http;
use Webkul\Checkout\Models\Cart;
use Webkul\Paypal\Http\Controllers\StandardController;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderTransaction;

use function Pest\Laravel\post;
use function Pest\Laravel\withSession;

/**
 * The PayPal account the store is configured to be paid into.
 */
const IPN_BUSINESS_ACCOUNT = 'store@example.com';

/**
 * A verified Completed IPN for the cart, as PayPal posts one.
 */
function paypalIpnPayload(Cart $cart, array $overrides = []): array
{
    return array_merge([
        'txn_type' => 'web_accept',
        'txn_id' => 'TESTTXN'.$cart->id,
        'invoice' => $cart->id,
        'payment_status' => 'Completed',
        'payment_type' => 'instant',
        'receiver_email' => IPN_BUSINESS_ACCOUNT,
        'business' => IPN_BUSINESS_ACCOUNT,
        'mc_gross' => $cart->grand_total,
        'mc_currency' => $cart->cart_currency_code,
    ], $overrides);
}

/**
 * Post an IPN the way PayPal does, with its postback answering VERIFIED unless told otherwise.
 */
function postPaypalIpn(array $payload, string $postback = 'VERIFIED')
{
    Http::fake(['*paypal.com/*' => Http::response($postback)]);

    return post(route('paypal.standard.ipn'), $payload);
}

beforeEach(function () {
    $this->setConfig('sales.payment_methods.paypal_standard.business_account', IPN_BUSINESS_ACCOUNT);
});

// ============================================================================
// A Buyer Who Never Came Back
// ============================================================================

it('should place the order from the ipn when the buyer never returned to the store', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    postPaypalIpn(paypalIpnPayload($cart))->assertOk();

    $order = Order::query()->where('cart_id', $cart->id)->firstOrFail();

    expect($order->status)->toBe(Order::STATUS_PROCESSING)
        ->and($order->invoices)->toHaveCount(1)
        ->and($cart->refresh()->is_active)->toBeFalsy();

    expect(OrderTransaction::query()->where('order_id', $order->id)->value('transaction_id'))
        ->toBe('TESTTXN'.$cart->id);
});

it('should place one order however many times paypal retries the ipn', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    postPaypalIpn(paypalIpnPayload($cart))->assertOk();

    postPaypalIpn(paypalIpnPayload($cart))->assertOk();

    expect(Order::query()->where('cart_id', $cart->id)->count())->toBe(1)
        ->and(OrderTransaction::query()->where('order_id', Order::query()->where('cart_id', $cart->id)->value('id'))->count())
        ->toBe(1);
});

it('should show the buyer the order the ipn placed while they were away', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    postPaypalIpn(paypalIpnPayload($cart))->assertOk();

    $order = Order::query()->where('cart_id', $cart->id)->firstOrFail();

    withSession([StandardController::INTENT_KEY => $cart->id])
        ->get(route('paypal.standard.success'))
        ->assertRedirect(route('shop.checkout.onepage.success'))
        ->assertSessionHas('order_id', $order->id);

    expect(Order::query()->where('cart_id', $cart->id)->count())->toBe(1);
});

// ============================================================================
// A Buyer Who Came Back First
// ============================================================================

it('should settle the order the returning buyer placed rather than placing a second one', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    withSession([StandardController::INTENT_KEY => $cart->id])
        ->get(route('paypal.standard.success'))
        ->assertRedirect(route('shop.checkout.onepage.success'));

    postPaypalIpn(paypalIpnPayload($cart))->assertOk();

    $order = Order::query()->where('cart_id', $cart->id)->firstOrFail();

    expect(Order::query()->where('cart_id', $cart->id)->count())->toBe(1)
        ->and($order->status)->toBe(Order::STATUS_PROCESSING)
        ->and($order->invoices)->toHaveCount(1);
});

// ============================================================================
// An Ipn The Store Must Not Act On
// ============================================================================

it('should place no order for an ipn paypal does not verify', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    postPaypalIpn(paypalIpnPayload($cart), 'INVALID')->assertOk();

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse()
        ->and($cart->refresh()->is_active)->toBeTruthy();
});

it('should place no order when the payment was made to another paypal account', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    postPaypalIpn(paypalIpnPayload($cart, [
        'receiver_email' => 'someone-else@example.com',
        'business' => 'someone-else@example.com',
    ]))->assertOk();

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should place no order when the amount paid is less than the cart', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    postPaypalIpn(paypalIpnPayload($cart, ['mc_gross' => $cart->grand_total - 1]))->assertOk();

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should place no order when the amount was paid in another currency', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    postPaypalIpn(paypalIpnPayload($cart, ['mc_currency' => 'INR']))->assertOk();

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should place no order for a payment status other than completed', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    postPaypalIpn(paypalIpnPayload($cart, ['payment_status' => 'Pending']))->assertOk();

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should place no order for a recurring payment notification', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    postPaypalIpn(paypalIpnPayload($cart, ['txn_type' => 'recurring_payment']))->assertOk();

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should answer an ipn for a cart that no longer exists rather than failing', function () {
    $cart = $this->createCartWithItems('paypal_standard');

    $payload = paypalIpnPayload($cart, ['invoice' => $cart->id + 1000]);

    postPaypalIpn($payload)->assertOk();

    expect(Order::query()->where('cart_id', $payload['invoice'])->exists())->toBeFalse();
});
