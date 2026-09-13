<?php

use Webkul\Core\Models\CoreConfig;
use Webkul\Razorpay\Payment\RazorpayPayment;

beforeEach(function () {
    $this->razorpay = app(RazorpayPayment::class);
});

it('returns the correct payment method code', function () {
    $code = $this->razorpay->getCode();

    expect($code)->toBe('razorpay');
});

it('returns the payment method title from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.title',
        'value' => 'Razorpay Payment Gateway',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    $title = $this->razorpay->getTitle();

    expect($title)->toBe('Razorpay Payment Gateway');
});

it('returns the payment method description from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.description',
        'value' => 'Pay securely using Razorpay',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    $description = $this->razorpay->getDescription();

    expect($description)->toBe('Pay securely using Razorpay');
});

it('returns the client ID based on sandbox mode', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_id',
        'value' => 'test_key_123',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.client_id',
        'value' => 'live_key_456',
        'channel_code' => 'default',
    ]);

    $clientId = $this->razorpay->getApiKey();

    expect($clientId)->toBe('test_key_123');
});

it('returns the live client ID when sandbox mode is disabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_id',
        'value' => 'test_key_123',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.client_id',
        'value' => 'live_key_456',
        'channel_code' => 'default',
    ]);

    $clientId = $this->razorpay->getApiKey();

    expect($clientId)->toBe('live_key_456');
});

it('returns the client secret based on sandbox mode', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'test_secret_123',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.client_secret',
        'value' => 'live_secret_456',
        'channel_code' => 'default',
    ]);

    $clientSecret = $this->razorpay->getApiSecret();

    expect($clientSecret)->toBe('test_secret_123');
});

it('returns the live client secret when sandbox mode is disabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'test_secret_123',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.client_secret',
        'value' => 'live_secret_456',
        'channel_code' => 'default',
    ]);

    $clientSecret = $this->razorpay->getApiSecret();

    expect($clientSecret)->toBe('live_secret_456');
});

it('checks if credentials are valid in sandbox mode', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_id',
        'value' => 'test_key_123',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'test_secret_123',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('checks if credentials are valid in production mode', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.client_id',
        'value' => 'live_key_456',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.client_secret',
        'value' => 'live_secret_456',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('returns false if sandbox credentials are missing', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_id',
        'value' => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'test_secret_123',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('returns false if production credentials are missing', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.client_id',
        'value' => 'live_key_456',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.client_secret',
        'value' => '',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('is not available when credentials are invalid', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.test_client_id',
        'value' => '',
        'channel_code' => 'default',
    ]);

    $isAvailable = $this->razorpay->isAvailable();

    expect($isAvailable)->toBeFalse();
});

it('returns payment method image from config', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.image',
        'value' => 'razorpay/custom-logo.png',
        'channel_code' => 'default',
    ]);

    $image = $this->razorpay->getImage();

    expect($image)->toContain('razorpay/custom-logo.png');
});

it('returns default payment method image when not configured', function () {
    $image = $this->razorpay->getImage();

    expect($image)->toContain('razorpay')
        ->and($image)->toContain('.png');
});

it('checks if currency is supported', function () {
    $isINRSupported = $this->razorpay->isCurrencySupported('INR');

    $isUSDSupported = $this->razorpay->isCurrencySupported('USD');

    expect($isINRSupported)->toBeTrue()
        ->and($isUSDSupported)->toBeFalse();
});

it('returns supported currencies list', function () {
    $currencies = $this->razorpay->getSupportedCurrencies();

    expect($currencies)->toBeArray()
        ->and($currencies)->toContain('INR');
});

it('returns merchant name from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.merchant_name',
        'value' => 'Test Merchant',
        'channel_code' => 'default',
    ]);

    $merchantName = $this->razorpay->getMerchantName();

    expect($merchantName)->toBe('Test Merchant');
});

it('returns merchant description from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.razorpay.merchant_desc',
        'value' => 'Test Description',
        'channel_code' => 'default',
    ]);

    $merchantDescription = $this->razorpay->getMerchantDescription();

    expect($merchantDescription)->toBe('Test Description');
});
