<?php

use Stripe\Checkout\Session;
use Stripe\Event;
use Webkul\Core\Models\Currency;
use Webkul\Stripe\Payment\Stripe;

/**
 * Sign a payload the way Stripe signs a webhook with the given secret.
 */
function stripeSignatureFor(string $payload, string $secret, ?int $timestamp = null): string
{
    $timestamp ??= time();

    return 't='.$timestamp.',v1='.hash_hmac('sha256', $timestamp.'.'.$payload, $secret);
}

/**
 * Build the checkout session Stripe answers with.
 */
function stripeSessionResponse(string $id, string $paymentStatus = 'unpaid'): array
{
    return [
        'id' => $id,
        'object' => 'checkout.session',
        'url' => 'https://checkout.stripe.com/c/pay/'.$id,
        'payment_status' => $paymentStatus,
        'payment_intent' => 'pi_for_'.$id,
    ];
}

beforeEach(function () {
    $this->setConfig([
        'sales.payment_methods.stripe.sandbox' => '1',
        'sales.payment_methods.stripe.api_test_key' => 'sk_test_fake_key',
        'sales.payment_methods.stripe.webhook_secret' => 'whsec_live_secret',
        'sales.payment_methods.stripe.webhook_test_secret' => 'whsec_test_secret',
    ]);

    $this->stripe = app(Stripe::class);
});

// ============================================================================
// Webhook Signatures
// ============================================================================

it('should read the webhook signing secret of the mode the store runs in', function (string $sandbox, string $secret) {
    $this->setConfig('sales.payment_methods.stripe.sandbox', $sandbox);

    expect($this->stripe->getWebhookSecret())->toBe($secret);
})->with([
    'test mode' => ['1', 'whsec_test_secret'],
    'live mode' => ['0', 'whsec_live_secret'],
]);

it('should read an event Stripe signed with the signing secret', function () {
    $payload = json_encode([
        'id' => 'evt_test_signed',
        'object' => 'event',
        'type' => 'charge.refunded',
        'data' => ['object' => ['id' => 'ch_test_signed', 'object' => 'charge']],
    ]);

    $event = $this->stripe->constructWebhookEvent($payload, stripeSignatureFor($payload, 'whsec_test_secret'));

    expect($event)->toBeInstanceOf(Event::class)
        ->and($event->type)->toBe('charge.refunded')
        ->and($event->data->object->id)->toBe('ch_test_signed');
});

it('should refuse an event it cannot trust', function (string $case) {
    $payload = json_encode(['id' => 'evt_test_untrusted', 'object' => 'event', 'type' => 'charge.refunded']);

    $signature = match ($case) {
        'another secret' => stripeSignatureFor($payload, 'whsec_someone_else'),
        'a changed payload' => stripeSignatureFor(str_replace('refunded', 'captured', $payload), 'whsec_test_secret'),
        'an expired signature' => stripeSignatureFor($payload, 'whsec_test_secret', time() - 3600),
        'no signature' => '',
    };

    expect($this->stripe->constructWebhookEvent($payload, $signature))->toBeNull();
})->with([
    'another secret',
    'a changed payload',
    'an expired signature',
    'no signature',
]);

// ============================================================================
// Amounts
// ============================================================================

it('should convert an amount from the smallest unit of its currency', function () {
    $zeroDecimalCurrency = Currency::factory()->create(['decimal' => 0]);

    expect($this->stripe->fromStripeAmount(13250, core()->getBaseCurrencyCode()))->toBe(13250 / (10 ** (core()->getBaseCurrency()->decimal ?? 2)))
        ->and($this->stripe->fromStripeAmount(1500, strtolower($zeroDecimalCurrency->code)))->toBe(1500.0)
        ->and($this->stripe->fromStripeAmount(1500, 'zzz'))->toBe(15.0);
});

// ============================================================================
// Checkout Sessions
// ============================================================================

it('should start a checkout charging the items, shipping and tax, and noting the cart and its total', function () {
    $cart = $this->createCartWithItems('stripe');

    $cart->base_shipping_amount = 10;

    $cart->base_tax_total = 5;

    $client = $this->fakeStripeApi([
        'POST /v1/checkout/sessions' => [200, stripeSessionResponse('cs_test_started')],
    ]);

    $session = $this->stripe->createCheckoutSession($cart);

    $params = $client->requestsTo('POST', '/v1/checkout/sessions')[0]['params'];

    $unit = 10 ** (core()->getBaseCurrency()->decimal ?? 2);

    expect($session)->toBeInstanceOf(Session::class)
        ->and($session->url)->toBe('https://checkout.stripe.com/c/pay/cs_test_started')
        ->and($params['mode'])->toBe('payment')
        ->and($params['success_url'])->toStartWith(route('stripe.payment.success'))
        ->and($params['cancel_url'])->toStartWith(route('stripe.payment.cancel'))
        ->and($params['metadata'])->toBe([
            'cart_id' => $cart->id,
            'base_grand_total' => (string) $cart->base_grand_total,
        ])
        ->and(array_column(array_column($params['line_items'], 'price_data'), 'unit_amount'))->toBe([
            (int) round($cart->items->first()->base_price * $unit),
            10 * $unit,
            5 * $unit,
        ])
        ->and(array_column(array_column(array_column($params['line_items'], 'price_data'), 'product_data'), 'name'))->toBe([
            $cart->items->first()->product->name,
            trans('stripe::app.line-items.shipping'),
            trans('stripe::app.line-items.tax'),
        ]);
});

it('should hand back a checkout session only once it is paid', function () {
    $this->fakeStripeApi([
        'GET /v1/checkout/sessions/cs_test_paid' => [200, stripeSessionResponse('cs_test_paid', 'paid')],
        'GET /v1/checkout/sessions/cs_test_unpaid' => [200, stripeSessionResponse('cs_test_unpaid')],
    ]);

    expect($this->stripe->retrieveCheckoutSession('cs_test_paid')->id)->toBe('cs_test_paid')
        ->and($this->stripe->retrieveCheckoutSession('cs_test_unpaid'))->toBeFalse()
        ->and($this->stripe->retrieveCheckoutSession('cs_test_missing'))->toBeFalse();
});

it('should find the checkout session a payment intent was taken through', function () {
    $client = $this->fakeStripeApi([
        'GET /v1/checkout/sessions' => [200, [
            'object' => 'list',
            'data' => [stripeSessionResponse('cs_test_found', 'paid')],
            'has_more' => false,
            'url' => '/v1/checkout/sessions',
        ]],
    ]);

    expect($this->stripe->findCheckoutSession('pi_test_found')->id)->toBe('cs_test_found')
        ->and($client->requestsTo('GET', '/v1/checkout/sessions')[0]['params'])->toBe([
            'payment_intent' => 'pi_test_found',
            'limit' => 1,
        ]);
});

it('should find no checkout session for a payment no checkout took', function () {
    $this->fakeStripeApi([
        'GET /v1/checkout/sessions' => [200, [
            'object' => 'list',
            'data' => [],
            'has_more' => false,
            'url' => '/v1/checkout/sessions',
        ]],
    ]);

    expect($this->stripe->findCheckoutSession('pi_test_elsewhere'))->toBeNull();
});
