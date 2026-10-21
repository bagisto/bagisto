<?php

use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Paypal\Payment\SmartButton;
use Webkul\Sales\Models\Order;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * A PayPal order for the given amount, shaped the way the SDK returns one.
 */
function paypalOrder($cart, ?float $amount = null, string $status = 'APPROVED', int $units = 1): array
{
    $unit = [
        'amount' => [
            'value' => number_format($amount ?? $cart->grand_total, 2, '.', ''),
            'currency_code' => $cart->cart_currency_code,
        ],
    ];

    return [
        'id' => 'PAYPAL-ORDER-1',
        'intent' => 'CAPTURE',
        'status' => $status,
        'payer' => ['email_address' => $cart->customer_email],
        'purchase_units' => array_fill(0, $units, $unit),
    ];
}

/**
 * The same order as PayPal reports it once the money has been taken.
 */
function capturedPaypalOrder(array $order): array
{
    $order['status'] = 'COMPLETED';

    $order['purchase_units'][0]['payments']['captures'][] = [
        'id' => 'PAYPAL-CAPTURE-1',
        'amount' => $order['purchase_units'][0]['amount'],
    ];

    return $order;
}

/**
 * Swap the PayPal SDK for one returning the given order, asserting whether it captured.
 */
function fakeSmartButton(array $order, bool $captures): void
{
    test()->partialMock(SmartButton::class, function ($mock) use ($order, $captures) {
        if (! $captures) {
            $mock->shouldReceive('getOrder')->andReturn($order);

            $mock->shouldNotReceive('captureOrder');

            return;
        }

        $captured = false;

        $mock->shouldReceive('captureOrder')->once()->andReturnUsing(function () use (&$captured, $order) {
            $captured = true;

            return capturedPaypalOrder($order);
        });

        $mock->shouldReceive('getOrder')->andReturnUsing(function () use (&$captured, $order) {
            return $captured ? capturedPaypalOrder($order) : $order;
        });
    });
}

/**
 * Hit the capture endpoint for a PayPal order id.
 */
function captureSmartButtonOrder()
{
    return postJson(route('paypal.smart_button.capture_order'), [
        'orderData' => ['orderID' => 'PAYPAL-ORDER-1'],
    ]);
}

// ============================================================================
// Nothing Is Captured For An Amount That Is Not The Cart's
// ============================================================================

it('should not capture when PayPal approved less than the cart comes to', function () {
    $cart = $this->createCartWithItems('paypal_smart_button');

    fakeSmartButton(paypalOrder($cart, 1.00), captures: false);

    captureSmartButtonOrder()
        ->assertStatus(403)
        ->assertJsonPath('redirect_url', route('shop.checkout.cart.index'));

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should not capture an order the shopper has not approved', function () {
    $cart = $this->createCartWithItems('paypal_smart_button');

    fakeSmartButton(paypalOrder($cart, status: 'CREATED'), captures: false);

    captureSmartButtonOrder()->assertStatus(403);

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should not capture an order carrying more than one purchase unit', function () {
    $cart = $this->createCartWithItems('paypal_smart_button');

    fakeSmartButton(paypalOrder($cart, units: 2), captures: false);

    captureSmartButtonOrder()->assertStatus(403);

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should not capture when PayPal approved another currency', function () {
    $cart = $this->createCartWithItems('paypal_smart_button');

    $order = paypalOrder($cart);

    $order['purchase_units'][0]['amount']['currency_code'] = 'INR';

    fakeSmartButton($order, captures: false);

    captureSmartButtonOrder()->assertStatus(403);

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});

// ============================================================================
// A Matching Approval Still Places The Order
// ============================================================================

it('should capture and place the order when the approved amount is the cart total', function () {
    $cart = $this->createCartWithItems('paypal_smart_button');

    fakeSmartButton(paypalOrder($cart), captures: true);

    captureSmartButtonOrder()
        ->assertOk()
        ->assertJsonPath('success', true);

    expect(Order::query()->where('cart_id', $cart->id)->count())->toBe(1);
});

// ============================================================================
// A Switched Off Smart Button Serves Nothing
// ============================================================================

it('should not create or capture a PayPal order while the smart button is disabled', function () {
    $cart = $this->createCartWithItems('paypal_smart_button');

    $this->setConfig('sales.payment_methods.paypal_smart_button.active', 0);

    CacheGeneration::bump(CoreConfigRepository::class);

    fakeSmartButton(paypalOrder($cart), captures: false);

    get(route('paypal.smart_button.create_order'))->assertNotFound();

    captureSmartButtonOrder()->assertNotFound();

    expect(Order::query()->where('cart_id', $cart->id)->exists())->toBeFalse();
});
