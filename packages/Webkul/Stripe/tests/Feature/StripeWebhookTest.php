<?php

use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Stripe\Exception\UnknownApiErrorException;
use Webkul\Checkout\Models\Cart;
use Webkul\Core\Models\CoreConfig;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderComment;
use Webkul\Sales\Models\OrderTransaction;
use Webkul\Sales\Models\Refund;
use Webkul\Sales\Repositories\RefundRepository;
use Webkul\Stripe\Payment\Stripe;

/**
 * Post an event to the webhook, signed the way Stripe signs it with the given secret.
 */
function postStripeWebhookEvent(string $type, array $object, string $secret = 'whsec_test_bagisto'): TestResponse
{
    $payload = json_encode([
        'id' => 'evt_'.Str::random(14),
        'object' => 'event',
        'type' => $type,
        'data' => ['object' => $object],
    ]);

    $timestamp = time();

    return test()->call('POST', route('stripe.webhook'), [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_STRIPE_SIGNATURE' => 't='.$timestamp.',v1='.hash_hmac('sha256', $timestamp.'.'.$payload, $secret),
    ], $payload);
}

/**
 * Build the checkout session Stripe reports once the payment for a cart has gone through.
 */
function paidStripeCheckoutSession(Cart $cart, array $overrides = []): array
{
    return array_merge([
        'id' => 'cs_test_'.$cart->id,
        'object' => 'checkout.session',
        'payment_intent' => 'pi_test_'.$cart->id,
        'payment_status' => 'paid',
        'metadata' => [
            'cart_id' => (string) $cart->id,
            'base_grand_total' => (string) $cart->base_grand_total,
        ],
    ], $overrides);
}

/**
 * Build the list Stripe answers a search for checkout sessions with.
 */
function stripeCheckoutSessionList(array $sessions = []): array
{
    return [
        'object' => 'list',
        'data' => $sessions,
        'has_more' => false,
        'url' => '/v1/checkout/sessions',
    ];
}

/**
 * Place an order through the webhook for a new cart paid with Stripe.
 */
function placeStripeOrderByWebhook(): Order
{
    $cart = test()->createCartWithItems('stripe');

    postStripeWebhookEvent('checkout.session.completed', paidStripeCheckoutSession($cart))->assertOk();

    return Order::where('cart_id', $cart->id)->firstOrFail();
}

/**
 * Build the charge Stripe reports once some or all of an order's payment is refunded.
 */
function refundedStripeCharge(Order $order, float $refunded): array
{
    $unit = 10 ** (core()->getBaseCurrency()->decimal ?? 2);

    return [
        'id' => 'ch_test_'.$order->id,
        'object' => 'charge',
        'payment_intent' => 'pi_test_'.$order->cart_id,
        'currency' => strtolower($order->base_currency_code),
        'amount' => (int) round($order->base_grand_total * $unit),
        'amount_refunded' => (int) round($refunded * $unit),
        'refunded' => round($refunded, 2) >= round((float) $order->base_grand_total, 2),
    ];
}

beforeEach(function () {
    foreach ([
        'active' => '1',
        'sandbox' => '1',
        'api_test_key' => 'sk_test_fake_key',
        'api_test_publishable_key' => 'pk_test_fake_key',
        'webhook_test_secret' => 'whsec_test_bagisto',
    ] as $field => $value) {
        CoreConfig::updateOrCreate([
            'code' => 'sales.payment_methods.stripe.'.$field,
            'channel_code' => 'default',
        ], ['value' => $value]);
    }

    $this->fakeStripeApi([
        'GET /v1/checkout/sessions' => [200, stripeCheckoutSessionList()],
    ]);
});

it('should refuse an event Stripe did not sign with the configured secret', function () {
    $cart = $this->createCartWithItems('stripe');

    postStripeWebhookEvent('checkout.session.completed', paidStripeCheckoutSession($cart), 'whsec_someone_else')
        ->assertBadRequest();

    expect(Order::where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should refuse an event without a signature', function () {
    $cart = $this->createCartWithItems('stripe');

    $this->call('POST', route('stripe.webhook'), [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
        'id' => 'evt_test_unsigned',
        'object' => 'event',
        'type' => 'checkout.session.completed',
        'data' => ['object' => paidStripeCheckoutSession($cart)],
    ]))
        ->assertBadRequest()
        ->assertJsonPath('status', 'invalid_signature');

    expect(Order::where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should refuse every event while no signing secret is set', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.webhook_test_secret',
        'channel_code' => 'default',
    ], ['value' => '']);

    $cart = $this->createCartWithItems('stripe');

    postStripeWebhookEvent('checkout.session.completed', paidStripeCheckoutSession($cart), '')
        ->assertBadRequest();

    expect(Order::where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should acknowledge an event it has nothing to do for', function () {
    postStripeWebhookEvent('payment_intent.created', [
        'id' => 'pi_test_ignored',
        'object' => 'payment_intent',
    ])
        ->assertOk()
        ->assertJsonPath('status', 'ignored');
});

it('should place the order for a paid checkout the customer never came back from', function () {
    $cart = $this->createCartWithItems('stripe');

    postStripeWebhookEvent('checkout.session.completed', paidStripeCheckoutSession($cart))
        ->assertOk()
        ->assertJsonPath('status', 'order_placed');

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe(Order::STATUS_PROCESSING)
        ->and($order->invoices)->toHaveCount(1)
        ->and(OrderTransaction::where('transaction_id', 'pi_test_'.$cart->id)->value('order_id'))->toBe($order->id)
        ->and($cart->fresh()->is_active)->toBeFalsy();
});

it('should place one order however often Stripe and the customer report the payment', function () {
    $cart = $this->createCartWithItems('stripe');

    postStripeWebhookEvent('checkout.session.completed', paidStripeCheckoutSession($cart))->assertOk();

    postStripeWebhookEvent('checkout.session.completed', paidStripeCheckoutSession($cart))
        ->assertOk()
        ->assertJsonPath('status', 'order_placed');

    $stripe = $this->mock(Stripe::class)->makePartial();

    $stripe->shouldReceive('retrieveCheckoutSession')->andReturn((object) [
        'id' => 'cs_test_'.$cart->id,
        'payment_intent' => 'pi_test_'.$cart->id,
        'payment_status' => 'paid',
        'metadata' => (object) ['cart_id' => $cart->id],
    ]);

    $this->get(route('stripe.payment.success', ['session_id' => 'cs_test_'.$cart->id]))
        ->assertRedirect(route('shop.checkout.onepage.success'))
        ->assertSessionHas('order_id', Order::where('cart_id', $cart->id)->value('id'));

    expect(Order::where('cart_id', $cart->id)->count())->toBe(1);
});

it('should place the order for a delayed payment once Stripe reports it succeeded', function () {
    $cart = $this->createCartWithItems('stripe');

    postStripeWebhookEvent('checkout.session.async_payment_succeeded', paidStripeCheckoutSession($cart))
        ->assertOk()
        ->assertJsonPath('status', 'order_placed');

    expect(Order::where('cart_id', $cart->id)->value('status'))->toBe(Order::STATUS_PROCESSING);
});

it('should not place an order for a cart that changed after the payment started', function () {
    $cart = $this->createCartWithItems('stripe');

    postStripeWebhookEvent('checkout.session.completed', paidStripeCheckoutSession($cart, [
        'metadata' => [
            'cart_id' => (string) $cart->id,
            'base_grand_total' => (string) ($cart->base_grand_total - 1),
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('status', 'order_not_placed');

    expect(Order::where('cart_id', $cart->id)->exists())->toBeFalse()
        ->and($cart->fresh()->is_active)->toBeTruthy();
});

it('should not place an order for a checkout whose payment has not gone through', function () {
    $cart = $this->createCartWithItems('stripe');

    postStripeWebhookEvent('checkout.session.completed', paidStripeCheckoutSession($cart, ['payment_status' => 'unpaid']))
        ->assertOk()
        ->assertJsonPath('status', 'payment_not_confirmed');

    expect(Order::where('cart_id', $cart->id)->exists())->toBeFalse();
});

it('should record a partial refund made in Stripe once, however often Stripe reports it', function () {
    $order = placeStripeOrderByWebhook();

    $half = round($order->base_grand_total / 2, 2);

    postStripeWebhookEvent('charge.refunded', refundedStripeCharge($order, $half))
        ->assertJsonPath('status', 'refund_recorded');

    postStripeWebhookEvent('charge.refunded', refundedStripeCharge($order, $half))
        ->assertJsonPath('status', 'refund_already_recorded');

    $order->refresh();

    expect(Refund::where('order_id', $order->id)->count())->toBe(1)
        ->and((float) $order->base_grand_total_refunded)->toBe($half)
        ->and($order->status)->toBe(Order::STATUS_PROCESSING)
        ->and(OrderComment::where('order_id', $order->id)->count())->toBe(1);
});

it('should refund the items and close the order once Stripe refunds the whole payment', function () {
    $order = placeStripeOrderByWebhook();

    postStripeWebhookEvent('charge.refunded', refundedStripeCharge($order, (float) $order->base_grand_total))
        ->assertJsonPath('status', 'refund_recorded');

    $order->refresh();

    $refund = Refund::where('order_id', $order->id)->sole();

    expect((float) $order->base_grand_total_refunded)->toBe((float) $order->base_grand_total)
        ->and($order->status)->toBe(Order::STATUS_CLOSED)
        ->and($order->items->every(fn ($item) => $item->qty_refunded == $item->qty_ordered))->toBeTrue()
        ->and($refund->items)->toHaveCount($order->items->count())
        ->and((float) $refund->base_grand_total)->toBe((float) $order->base_grand_total);
});

it('should refund the remaining items once Stripe refunds the rest of a partly refunded payment', function () {
    $order = placeStripeOrderByWebhook();

    $half = round($order->base_grand_total / 2, 2);

    postStripeWebhookEvent('charge.refunded', refundedStripeCharge($order, $half))
        ->assertJsonPath('status', 'refund_recorded');

    postStripeWebhookEvent('charge.refunded', refundedStripeCharge($order, (float) $order->base_grand_total))
        ->assertJsonPath('status', 'refund_recorded');

    $order->refresh();

    $lastRefund = Refund::where('order_id', $order->id)->latest('id')->first();

    expect(Refund::where('order_id', $order->id)->count())->toBe(2)
        ->and((float) $order->base_grand_total_refunded)->toBe((float) $order->base_grand_total)
        ->and($order->status)->toBe(Order::STATUS_CLOSED)
        ->and($lastRefund->items)->not->toBeEmpty()
        ->and(round((float) $lastRefund->base_grand_total, 2))->toBe(round($order->base_grand_total - $half, 2));
});

it('should not record again a refund the merchant already entered in Bagisto', function () {
    $order = placeStripeOrderByWebhook();

    $amount = round($order->base_grand_total / 2, 2);

    app(RefundRepository::class)->create([
        'order_id' => $order->id,
        'refund' => [
            'items' => [],
            'shipping' => 0,
            'adjustment_refund' => $amount,
            'adjustment_fee' => 0,
        ],
    ]);

    postStripeWebhookEvent('charge.refunded', refundedStripeCharge($order, $amount))
        ->assertJsonPath('status', 'refund_already_recorded');

    expect(Refund::where('order_id', $order->id)->count())->toBe(1);
});

it('should note on the order when a dispute opens and when it closes, once each', function () {
    $order = placeStripeOrderByWebhook();

    $dispute = [
        'id' => 'dp_test_'.$order->id,
        'object' => 'dispute',
        'payment_intent' => 'pi_test_'.$order->cart_id,
        'amount' => 1000,
        'currency' => strtolower($order->base_currency_code),
        'reason' => 'product_not_received',
        'status' => 'needs_response',
    ];

    postStripeWebhookEvent('charge.dispute.created', $dispute)
        ->assertJsonPath('status', 'dispute_recorded');

    postStripeWebhookEvent('charge.dispute.created', $dispute)
        ->assertJsonPath('status', 'dispute_already_recorded');

    postStripeWebhookEvent('charge.dispute.closed', array_merge($dispute, ['status' => 'won']))
        ->assertJsonPath('status', 'dispute_recorded');

    $comments = OrderComment::where('order_id', $order->id)->orderBy('id')->pluck('comment');

    expect($comments)->toHaveCount(2)
        ->and($comments->first())->toContain('product not received')
        ->and($comments->last())->toContain('won');
});

it('should place the order first when a dispute arrives before it', function () {
    $cart = $this->createCartWithItems('stripe');

    $this->fakeStripeApi([
        'GET /v1/checkout/sessions' => [200, stripeCheckoutSessionList([paidStripeCheckoutSession($cart)])],
    ]);

    postStripeWebhookEvent('charge.dispute.created', [
        'id' => 'dp_test_early',
        'object' => 'dispute',
        'payment_intent' => 'pi_test_'.$cart->id,
        'amount' => 1000,
        'currency' => strtolower($cart->base_currency_code),
        'reason' => 'fraudulent',
        'status' => 'needs_response',
    ])
        ->assertOk()
        ->assertJsonPath('status', 'dispute_recorded');

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and(OrderComment::where('order_id', $order->id)->value('comment'))->toContain('fraudulent');
});

it('should place the order first when a refund arrives before it', function () {
    $cart = $this->createCartWithItems('stripe');

    $client = $this->fakeStripeApi([
        'GET /v1/checkout/sessions' => [200, stripeCheckoutSessionList([paidStripeCheckoutSession($cart)])],
    ]);

    $unit = 10 ** (core()->getBaseCurrency()->decimal ?? 2);

    postStripeWebhookEvent('charge.refunded', [
        'id' => 'ch_test_early',
        'object' => 'charge',
        'payment_intent' => 'pi_test_'.$cart->id,
        'currency' => strtolower($cart->base_currency_code),
        'amount' => (int) round($cart->base_grand_total * $unit),
        'amount_refunded' => $unit,
        'refunded' => false,
    ])
        ->assertOk()
        ->assertJsonPath('status', 'refund_recorded');

    $order = Order::where('cart_id', $cart->id)->first();

    expect($order)->not->toBeNull()
        ->and((float) $order->base_grand_total_refunded)->toBe(1.0)
        ->and($client->requestsTo('GET', '/v1/checkout/sessions')[0]['params'])->toMatchArray([
            'payment_intent' => 'pi_test_'.$cart->id,
        ]);
});

it('should ask Stripe to send an event again when Stripe cannot be reached to handle it', function () {
    Exceptions::fake();

    $this->fakeStripeApi([
        'GET /v1/checkout/sessions' => [500, ['error' => ['type' => 'api_error', 'message' => 'Unavailable.']]],
    ]);

    postStripeWebhookEvent('charge.dispute.created', [
        'id' => 'dp_test_unreachable',
        'object' => 'dispute',
        'payment_intent' => 'pi_test_unreachable',
        'amount' => 1000,
        'currency' => 'usd',
        'reason' => 'fraudulent',
        'status' => 'needs_response',
    ])
        ->assertServerError()
        ->assertJsonPath('status', 'error');

    Exceptions::assertReported(UnknownApiErrorException::class);
});

it('should leave alone an event about a payment the store did not take', function () {
    postStripeWebhookEvent('charge.refunded', [
        'id' => 'ch_test_elsewhere',
        'object' => 'charge',
        'payment_intent' => 'pi_test_elsewhere',
        'currency' => 'usd',
        'amount' => 1000,
        'amount_refunded' => 1000,
        'refunded' => true,
    ])
        ->assertOk()
        ->assertJsonPath('status', 'order_not_found');
});
