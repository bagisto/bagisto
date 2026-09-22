<?php

use Webkul\Core\Models\CoreConfig;
use Webkul\Stripe\Payment\Stripe;

beforeEach(function () {
    $this->stripe = app(Stripe::class);
});

it('should return the stripe payment method code', function () {
    $code = $this->stripe->getCode();

    expect($code)->toBe('stripe');
});

it('should return the payment method title from the configuration', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.title',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ], ['value' => 'Stripe Payment Gateway']);

    $title = $this->stripe->getTitle();

    expect($title)->toBe('Stripe Payment Gateway');
});

it('should return the payment method description from the configuration', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.description',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ], ['value' => 'Pay securely using Stripe']);

    $description = $this->stripe->getDescription();

    expect($description)->toBe('Pay securely using Stripe');
});

it('should return the test API key in sandbox mode', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '1']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'channel_code' => 'default',
    ], ['value' => 'test_key']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_key',
        'channel_code' => 'default',
    ], ['value' => 'live_key']);

    $apiKey = $this->stripe->getApiKey();

    expect($apiKey)->toBe('test_key');
});

it('should return the live API key when sandbox mode is off', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '0']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'channel_code' => 'default',
    ], ['value' => 'test_key']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_key',
        'channel_code' => 'default',
    ], ['value' => 'live_key']);

    $apiKey = $this->stripe->getApiKey();

    expect($apiKey)->toBe('live_key');
});

it('should return the test publishable key in sandbox mode', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '1']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'channel_code' => 'default',
    ], ['value' => 'test_pub_key']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_publishable_key',
        'channel_code' => 'default',
    ], ['value' => 'live_pub_key']);

    $publishableKey = $this->stripe->getPublishableKey();

    expect($publishableKey)->toBe('test_pub_key');
});

it('should return the live publishable key when sandbox mode is off', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '0']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'channel_code' => 'default',
    ], ['value' => 'test_pub_key']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_publishable_key',
        'channel_code' => 'default',
    ], ['value' => 'live_pub_key']);

    $publishableKey = $this->stripe->getPublishableKey();

    expect($publishableKey)->toBe('live_pub_key');
});

it('should accept the credentials set for sandbox mode', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '1']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'channel_code' => 'default',
    ], ['value' => 'test_key']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'channel_code' => 'default',
    ], ['value' => 'test_pub_key']);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('should accept the credentials set for production mode', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '0']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_key',
        'channel_code' => 'default',
    ], ['value' => 'live_key']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_publishable_key',
        'channel_code' => 'default',
    ], ['value' => 'live_pub_key']);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('should refuse sandbox mode when its credentials are missing', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '1']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'channel_code' => 'default',
    ], ['value' => '']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'channel_code' => 'default',
    ], ['value' => 'test_pub_key']);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should refuse production mode when its credentials are missing', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '0']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_key',
        'channel_code' => 'default',
    ], ['value' => 'live_key']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_publishable_key',
        'channel_code' => 'default',
    ], ['value' => '']);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should not be offered at checkout when the credentials are invalid', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.active',
        'channel_code' => 'default',
    ], ['value' => '1']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.sandbox',
        'channel_code' => 'default',
    ], ['value' => '1']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_key',
        'channel_code' => 'default',
    ], ['value' => '']);

    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.api_test_publishable_key',
        'channel_code' => 'default',
    ], ['value' => '']);

    $isAvailable = $this->stripe->isAvailable();

    expect($isAvailable)->toBeFalse();
});

it('should return the payment method image from the configuration', function () {
    CoreConfig::updateOrCreate([
        'code' => 'sales.payment_methods.stripe.image',
        'channel_code' => 'default',
    ], ['value' => 'stripe/custom-logo.png']);

    $image = $this->stripe->getImage();

    expect($image)->toContain('stripe/custom-logo.png');
});

it('should return the default payment method image when none is configured', function () {
    $image = $this->stripe->getImage();

    expect($image)->toContain('stripe')
        ->and($image)->toContain('.png');
});

it('should send the customer to the stripe redirect route', function () {
    $url = $this->stripe->getRedirectUrl();

    expect($url)->toBe(route('stripe.standard.redirect'));
});
