<?php

use Webkul\Core\Models\CoreConfig;
use Webkul\Stripe\Payment\Stripe;

beforeEach(function () {
    $this->stripe = app(Stripe::class);
});

it('returns the correct payment method code', function () {
    $code = $this->stripe->getCode();

    expect($code)->toBe('stripe');
});

it('returns the payment method title from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.title',
        'value' => 'Stripe Payment Gateway',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    $title = $this->stripe->getTitle();

    expect($title)->toBe('Stripe Payment Gateway');
});

it('returns the payment method description from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.description',
        'value' => 'Pay securely using Stripe',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    $description = $this->stripe->getDescription();

    expect($description)->toBe('Pay securely using Stripe');
});

it('returns the API key based on sandbox mode', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'value' => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_key',
        'value' => 'live_key',
        'channel_code' => 'default',
    ]);

    $apiKey = $this->stripe->getApiKey();

    expect($apiKey)->toBe('test_key');
});

it('returns the live API key when sandbox mode is disabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'value' => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_key',
        'value' => 'live_key',
        'channel_code' => 'default',
    ]);

    $apiKey = $this->stripe->getApiKey();

    expect($apiKey)->toBe('live_key');
});

it('returns the publishable key based on sandbox mode', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value' => 'test_pub_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_publishable_key',
        'value' => 'live_pub_key',
        'channel_code' => 'default',
    ]);

    $publishableKey = $this->stripe->getPublishableKey();

    expect($publishableKey)->toBe('test_pub_key');
});

it('returns the live publishable key when sandbox mode is disabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value' => 'test_pub_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_publishable_key',
        'value' => 'live_pub_key',
        'channel_code' => 'default',
    ]);

    $publishableKey = $this->stripe->getPublishableKey();

    expect($publishableKey)->toBe('live_pub_key');
});

it('checks if credentials are valid in sandbox mode', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'value' => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value' => 'test_pub_key',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('checks if credentials are valid in production mode', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_key',
        'value' => 'live_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_publishable_key',
        'value' => 'live_pub_key',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('returns false if sandbox credentials are missing', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value' => 'test_pub_key',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('returns false if production credentials are missing', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_key',
        'value' => 'live_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_publishable_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('is not available when credentials are invalid', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    $isAvailable = $this->stripe->isAvailable();

    expect($isAvailable)->toBeFalse();
});

it('returns payment method image from config', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.stripe.image',
        'value' => 'stripe/custom-logo.png',
        'channel_code' => 'default',
    ]);

    $image = $this->stripe->getImage();

    expect($image)->toContain('stripe/custom-logo.png');
});

it('returns default payment method image when not configured', function () {
    $image = $this->stripe->getImage();

    expect($image)->toContain('stripe')
        ->and($image)->toContain('.png');
});

it('returns the correct redirect URL', function () {
    $url = $this->stripe->getRedirectUrl();

    expect($url)->toBe(route('stripe.standard.redirect'));
});
