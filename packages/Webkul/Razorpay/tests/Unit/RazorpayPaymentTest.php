<?php

use Webkul\Razorpay\Payment\RazorpayPayment;

beforeEach(function () {
    $this->razorpay = app(RazorpayPayment::class);
});

// ============================================================================
// Configuration
// ============================================================================

it('should return the correct payment method code', function () {
    $code = $this->razorpay->getCode();

    expect($code)->toBe('razorpay');
});

it('should return the payment method title from configuration', function () {
    $this->setConfig('sales.payment_methods.razorpay.title', 'Razorpay Payment Gateway');

    $title = $this->razorpay->getTitle();

    expect($title)->toBe('Razorpay Payment Gateway');
});

it('should return the payment method description from configuration', function () {
    $this->setConfig('sales.payment_methods.razorpay.description', 'Pay securely using Razorpay');

    $description = $this->razorpay->getDescription();

    expect($description)->toBe('Pay securely using Razorpay');
});

it('should return the payment method image from configuration', function () {
    $this->setConfig('sales.payment_methods.razorpay.image', 'razorpay/custom-logo.png');

    $image = $this->razorpay->getImage();

    expect($image)->toContain('razorpay/custom-logo.png');
});

it('should return the default payment method image when not configured', function () {
    $image = $this->razorpay->getImage();

    expect($image)->toContain('razorpay')
        ->and($image)->toContain('.png');
});

it('should return the merchant name from configuration', function () {
    $this->setConfig('sales.payment_methods.razorpay.merchant_name', 'Test Merchant');

    $merchantName = $this->razorpay->getMerchantName();

    expect($merchantName)->toBe('Test Merchant');
});

it('should return the merchant description from configuration', function () {
    $this->setConfig('sales.payment_methods.razorpay.merchant_desc', 'Test Description');

    $merchantDescription = $this->razorpay->getMerchantDescription();

    expect($merchantDescription)->toBe('Test Description');
});

// ============================================================================
// Credentials
// ============================================================================

it('should return the test client ID when sandbox mode is enabled', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.sandbox' => '1',
        'sales.payment_methods.razorpay.test_client_id' => 'test_key_123',
        'sales.payment_methods.razorpay.client_id' => 'live_key_456',
    ]);

    $clientId = $this->razorpay->getApiKey();

    expect($clientId)->toBe('test_key_123');
});

it('should return the live client ID when sandbox mode is disabled', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.sandbox' => '0',
        'sales.payment_methods.razorpay.test_client_id' => 'test_key_123',
        'sales.payment_methods.razorpay.client_id' => 'live_key_456',
    ]);

    $clientId = $this->razorpay->getApiKey();

    expect($clientId)->toBe('live_key_456');
});

it('should return the test client secret when sandbox mode is enabled', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.sandbox' => '1',
        'sales.payment_methods.razorpay.test_client_secret' => 'test_secret_123',
        'sales.payment_methods.razorpay.client_secret' => 'live_secret_456',
    ]);

    $clientSecret = $this->razorpay->getApiSecret();

    expect($clientSecret)->toBe('test_secret_123');
});

it('should return the live client secret when sandbox mode is disabled', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.sandbox' => '0',
        'sales.payment_methods.razorpay.test_client_secret' => 'test_secret_123',
        'sales.payment_methods.razorpay.client_secret' => 'live_secret_456',
    ]);

    $clientSecret = $this->razorpay->getApiSecret();

    expect($clientSecret)->toBe('live_secret_456');
});

it('should report the credentials valid in sandbox mode', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.sandbox' => '1',
        'sales.payment_methods.razorpay.test_client_id' => 'test_key_123',
        'sales.payment_methods.razorpay.test_client_secret' => 'test_secret_123',
    ]);

    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('should report the credentials valid in production mode', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.sandbox' => '0',
        'sales.payment_methods.razorpay.client_id' => 'live_key_456',
        'sales.payment_methods.razorpay.client_secret' => 'live_secret_456',
    ]);

    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('should report the credentials invalid when the sandbox credentials are missing', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.sandbox' => '1',
        'sales.payment_methods.razorpay.test_client_id' => '',
        'sales.payment_methods.razorpay.test_client_secret' => 'test_secret_123',
    ]);

    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should report the credentials invalid when the production credentials are missing', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.sandbox' => '0',
        'sales.payment_methods.razorpay.client_id' => 'live_key_456',
        'sales.payment_methods.razorpay.client_secret' => '',
    ]);

    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should not be available when the credentials are invalid', function () {
    $this->setConfig([
        'sales.payment_methods.razorpay.active' => '1',
        'sales.payment_methods.razorpay.sandbox' => '1',
        'sales.payment_methods.razorpay.test_client_id' => '',
    ]);

    $isAvailable = $this->razorpay->isAvailable();

    expect($isAvailable)->toBeFalse();
});

// ============================================================================
// Currencies
// ============================================================================

it('should support INR and not USD', function () {
    $isINRSupported = $this->razorpay->isCurrencySupported('INR');

    $isUSDSupported = $this->razorpay->isCurrencySupported('USD');

    expect($isINRSupported)->toBeTrue()
        ->and($isUSDSupported)->toBeFalse();
});

it('should return the list of supported currencies', function () {
    $currencies = $this->razorpay->getSupportedCurrencies();

    expect($currencies)->toBeArray()
        ->and($currencies)->toContain('INR');
});
