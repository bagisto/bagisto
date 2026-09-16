<?php

use Webkul\Stripe\Payment\Stripe;

beforeEach(function () {
    $this->stripe = app(Stripe::class);
});

// ============================================================================
// Configuration
// ============================================================================

it('should return the correct payment method code', function () {
    $code = $this->stripe->getCode();

    expect($code)->toBe('stripe');
});

it('should return the payment method title from configuration', function () {
    $this->setConfig('sales.payment_methods.stripe.title', 'Stripe Payment Gateway');

    $title = $this->stripe->getTitle();

    expect($title)->toBe('Stripe Payment Gateway');
});

it('should return the payment method description from configuration', function () {
    $this->setConfig('sales.payment_methods.stripe.description', 'Pay securely using Stripe');

    $description = $this->stripe->getDescription();

    expect($description)->toBe('Pay securely using Stripe');
});

it('should return the payment method image from configuration', function () {
    $this->setConfig('sales.payment_methods.stripe.image', 'stripe/custom-logo.png');

    $image = $this->stripe->getImage();

    expect($image)->toContain('stripe/custom-logo.png');
});

it('should return the default payment method image when not configured', function () {
    $image = $this->stripe->getImage();

    expect($image)->toContain('stripe')
        ->and($image)->toContain('.png');
});

it('should return the correct redirect URL', function () {
    $url = $this->stripe->getRedirectUrl();

    expect($url)->toBe(route('stripe.standard.redirect'));
});

// ============================================================================
// Keys
// ============================================================================

it('should return the test API key when sandbox mode is enabled', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.sandbox' => '1',
        'sales.payment_methods.stripe.api_test_key' => 'test_key',
        'sales.payment_methods.stripe.api_key' => 'live_key',
    ]);

    $apiKey = $this->stripe->getApiKey();

    expect($apiKey)->toBe('test_key');
});

it('should return the live API key when sandbox mode is disabled', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.sandbox' => '0',
        'sales.payment_methods.stripe.api_test_key' => 'test_key',
        'sales.payment_methods.stripe.api_key' => 'live_key',
    ]);

    $apiKey = $this->stripe->getApiKey();

    expect($apiKey)->toBe('live_key');
});

it('should return the test publishable key when sandbox mode is enabled', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.sandbox' => '1',
        'sales.payment_methods.stripe.api_test_publishable_key' => 'test_pub_key',
        'sales.payment_methods.stripe.api_publishable_key' => 'live_pub_key',
    ]);

    $publishableKey = $this->stripe->getPublishableKey();

    expect($publishableKey)->toBe('test_pub_key');
});

it('should return the live publishable key when sandbox mode is disabled', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.sandbox' => '0',
        'sales.payment_methods.stripe.api_test_publishable_key' => 'test_pub_key',
        'sales.payment_methods.stripe.api_publishable_key' => 'live_pub_key',
    ]);

    $publishableKey = $this->stripe->getPublishableKey();

    expect($publishableKey)->toBe('live_pub_key');
});

// ============================================================================
// Credentials
// ============================================================================

it('should report the credentials valid in sandbox mode', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.sandbox' => '1',
        'sales.payment_methods.stripe.api_test_key' => 'test_key',
        'sales.payment_methods.stripe.api_test_publishable_key' => 'test_pub_key',
    ]);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('should report the credentials valid in production mode', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.sandbox' => '0',
        'sales.payment_methods.stripe.api_key' => 'live_key',
        'sales.payment_methods.stripe.api_publishable_key' => 'live_pub_key',
    ]);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('should report the credentials invalid when the sandbox credentials are missing', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.sandbox' => '1',
        'sales.payment_methods.stripe.api_test_key' => '',
        'sales.payment_methods.stripe.api_test_publishable_key' => 'test_pub_key',
    ]);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should report the credentials invalid when the production credentials are missing', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.sandbox' => '0',
        'sales.payment_methods.stripe.api_key' => 'live_key',
        'sales.payment_methods.stripe.api_publishable_key' => '',
    ]);

    $hasValidCredentials = $this->stripe->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should not be available when the credentials are invalid', function () {
    $this->setConfig([
        'sales.payment_methods.stripe.active' => '1',
        'sales.payment_methods.stripe.sandbox' => '1',
        'sales.payment_methods.stripe.api_test_key' => '',
        'sales.payment_methods.stripe.api_test_publishable_key' => '',
    ]);

    $isAvailable = $this->stripe->isAvailable();

    expect($isAvailable)->toBeFalse();
});
