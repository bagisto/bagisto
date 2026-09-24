<?php

use Laravel\Ai\AnonymousAgent;

use function Pest\Laravel\get;
use function Pest\Laravel\withSession;

// ============================================================================
// Generated Message
// ============================================================================

it('should escape the markup a generated checkout message carries', function () {
    $order = $this->createOrder();

    $this->setConfig([
        'magic_ai.general.settings.enabled' => 1,
        'magic_ai.storefront_features.checkout_message.enabled' => 1,
    ]);

    AnonymousAgent::fake(fn () => 'Thank you <script>alert(1)</script> for your order.');

    withSession(['order_id' => $order->id]);

    $response = get(route('shop.checkout.onepage.success'))->assertOk();

    expect($response->getContent())
        ->toContain('&lt;script&gt;alert(1)&lt;/script&gt;')
        ->not->toContain('<script>alert(1)</script>');
});

it('should leave the checkout message out while the feature is switched off', function () {
    $order = $this->createOrder();

    $this->setConfig([
        'magic_ai.general.settings.enabled' => 1,
        'magic_ai.storefront_features.checkout_message.enabled' => 0,
    ]);

    AnonymousAgent::fake(fn () => 'Generated message.');

    withSession(['order_id' => $order->id]);

    get(route('shop.checkout.onepage.success'))
        ->assertOk()
        ->assertDontSee('Generated message.');
});
