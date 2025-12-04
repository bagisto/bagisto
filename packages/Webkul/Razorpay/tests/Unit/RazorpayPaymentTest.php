<?php

use Webkul\Core\Models\CoreConfig;
use Webkul\Razorpay\Payment\RazorpayPayment;

beforeEach(function () {
    $this->razorpay = app(RazorpayPayment::class);
});

it('returns the correct payment method code', function () {
    // Act
    $code = $this->razorpay->getCode();

    // Assert
    expect($code)->toBe('razorpay');
});

it('returns the payment method title from configuration', function () {
    // Act
    $title = $this->razorpay->getTitle();

    // Assert
    expect($title)->toBeString()
        ->and($title)->not->toBeEmpty();
});

it('returns the payment method description from configuration', function () {
    // Act
    $description = $this->razorpay->getDescription();

    // Assert
    expect($description)->toBeString()
        ->and($description)->not->toBeEmpty();
});

it('returns the client ID based on sandbox mode', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_id',
        'value' => 'test_key_123',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.client_id',
        'value' => 'live_key_456',
    ]);

    // Act
    $clientId = $this->razorpay->getApiKey();

    // Assert
    expect($clientId)->toBe('test_key_123');
});

it('returns the live client ID when sandbox mode is disabled', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '0',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_id',
        'value' => 'test_key_123',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.client_id',
        'value' => 'live_key_456',
    ]);

    // Act
    $clientId = $this->razorpay->getApiKey();

    // Assert
    expect($clientId)->toBe('live_key_456');
});

it('returns the client secret based on sandbox mode', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'test_secret_123',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.client_secret',
        'value' => 'live_secret_456',
    ]);

    // Act
    $clientSecret = $this->razorpay->getApiSecret();

    // Assert
    expect($clientSecret)->toBe('test_secret_123');
});

it('returns the live client secret when sandbox mode is disabled', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '0',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'test_secret_123',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.client_secret',
        'value' => 'live_secret_456',
    ]);

    // Act
    $clientSecret = $this->razorpay->getApiSecret();

    // Assert
    expect($clientSecret)->toBe('live_secret_456');
});

it('checks if credentials are valid in sandbox mode', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_id',
        'value' => 'test_key_123',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'test_secret_123',
    ]);

    // Act
    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeTrue();
});

it('checks if credentials are valid in production mode', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '0',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.client_id',
        'value' => 'live_key_456',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.client_secret',
        'value' => 'live_secret_456',
    ]);

    // Act
    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeTrue();
});

it('returns false if sandbox credentials are missing', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_id',
        'value' => '',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_secret',
        'value' => 'test_secret_123',
    ]);

    // Act
    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeFalse();
});

it('returns false if production credentials are missing', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '0',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.client_id',
        'value' => 'live_key_456',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.client_secret',
        'value' => '',
    ]);

    // Act
    $hasValidCredentials = $this->razorpay->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeFalse();
});

it('is not available when credentials are invalid', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.active',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.sandbox',
        'value' => '1',
    ]);

    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.test_client_id',
        'value' => '',
    ]);

    // Act
    $isAvailable = $this->razorpay->isAvailable();

    // Assert
    expect($isAvailable)->toBeFalse();
});

it('returns payment method image from config', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.image',
        'value' => 'razorpay/custom-logo.png',
    ]);

    // Act
    $image = $this->razorpay->getImage();

    // Assert
    expect($image)->toContain('razorpay/custom-logo.png');
});

it('returns default payment method image when not configured', function () {
    // Act
    $image = $this->razorpay->getImage();

    // Assert
    // The image path includes a Vite asset hash, so just check it contains the filename
    expect($image)->toContain('razorpay')
        ->and($image)->toContain('.png');
});

it('checks if currency is supported', function () {
    // Act
    $isINRSupported = $this->razorpay->isCurrencySupported('INR');

    $isUSDSupported = $this->razorpay->isCurrencySupported('USD');

    // Assert
    expect($isINRSupported)->toBeTrue()
        ->and($isUSDSupported)->toBeFalse();
});

it('returns supported currencies list', function () {
    // Act
    $currencies = $this->razorpay->getSupportedCurrencies();

    // Assert
    expect($currencies)->toBeArray()
        ->and($currencies)->toContain('INR');
});

it('returns merchant name from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.merchant_name',
        'value' => 'Test Merchant',
    ]);

    // Act
    $merchantName = $this->razorpay->getMerchantName();

    // Assert
    expect($merchantName)->toBe('Test Merchant');
});

it('returns merchant description from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'  => 'sales.payment_methods.razorpay.merchant_desc',
        'value' => 'Test Description',
    ]);

    // Act
    $merchantDescription = $this->razorpay->getMerchantDescription();

    // Assert
    expect($merchantDescription)->toBe('Test Description');
});
