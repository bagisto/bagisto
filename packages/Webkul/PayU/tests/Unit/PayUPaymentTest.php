<?php

use Webkul\Core\Models\CoreConfig;
use Webkul\PayU\Payment\PayU;

beforeEach(function () {
    $this->payU = app(PayU::class);
});

it('returns the correct payment method code', function () {
    // Act
    $code = $this->payU->getCode();

    // Assert
    expect($code)->toBe('payu');
});

it('returns the payment method title from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.title',
        'value'        => 'PayU Payment Gateway',
        'channel_code' => 'default',
        'locale_code'  => 'en',
    ]);

    // Act
    $title = $this->payU->getTitle();

    // Assert
    expect($title)->toBe('PayU Payment Gateway');
});

it('returns the payment method description from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.description',
        'value'        => 'Pay securely using PayU',
        'channel_code' => 'default',
        'locale_code'  => 'en',
    ]);

    // Act
    $description = $this->payU->getDescription();

    // Assert
    expect($description)->toBe('Pay securely using PayU');
});

it('returns the merchant key from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => 'test_merchant_key_123',
        'channel_code' => 'default',
    ]);

    // Act
    $merchantKey = $this->payU->getMerchantKey();

    // Assert
    expect($merchantKey)->toBe('test_merchant_key_123');
});

it('returns the merchant salt from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_salt',
        'value'        => 'test_merchant_salt_456',
        'channel_code' => 'default',
    ]);

    // Act
    $merchantSalt = $this->payU->getMerchantSalt();

    // Assert
    expect($merchantSalt)->toBe('test_merchant_salt_456');
});

it('checks if sandbox mode is enabled', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.sandbox',
        'value'        => '1',
        'channel_code' => 'default',
    ]);

    // Act
    $isSandbox = $this->payU->isSandbox();

    // Assert
    expect($isSandbox)->toBeTrue();
});

it('checks if sandbox mode is disabled', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.sandbox',
        'value'        => '0',
        'channel_code' => 'default',
    ]);

    // Act
    $isSandbox = $this->payU->isSandbox();

    // Assert
    expect($isSandbox)->toBeFalse();
});

it('returns sandbox payment URL when sandbox is enabled', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.sandbox',
        'value'        => '1',
        'channel_code' => 'default',
    ]);

    // Act
    $paymentUrl = $this->payU->getPaymentUrl();

    // Assert
    expect($paymentUrl)->toBe('https://test.payu.in/_payment');
});

it('returns production payment URL when sandbox is disabled', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.sandbox',
        'value'        => '0',
        'channel_code' => 'default',
    ]);

    // Act
    $paymentUrl = $this->payU->getPaymentUrl();

    // Assert
    expect($paymentUrl)->toBe('https://secure.payu.in/_payment');
});

it('checks if credentials are valid', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_salt',
        'value'        => 'test_salt',
        'channel_code' => 'default',
    ]);

    // Act
    $hasValidCredentials = $this->payU->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeTrue();
});

it('returns false if merchant key is missing', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_salt',
        'value'        => 'test_salt',
        'channel_code' => 'default',
    ]);

    // Act
    $hasValidCredentials = $this->payU->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeFalse();
});

it('returns false if merchant salt is missing', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_salt',
        'value'        => '',
        'channel_code' => 'default',
    ]);

    // Act
    $hasValidCredentials = $this->payU->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeFalse();
});

it('is not available when credentials are invalid', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.active',
        'value'        => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => '',
        'channel_code' => 'default',
    ]);

    // Act
    $isAvailable = $this->payU->isAvailable();

    // Assert
    expect($isAvailable)->toBeFalse();
});

it('returns payment method image from config', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.image',
        'value'        => 'payu/custom-logo.png',
        'channel_code' => 'default',
    ]);

    // Act
    $image = $this->payU->getImage();

    // Assert
    expect($image)->toContain('payu/custom-logo.png');
});

it('returns default payment method image when not configured', function () {
    // Act
    $image = $this->payU->getImage();

    // Assert
    expect($image)->toContain('payu')
        ->and($image)->toContain('.png');
});

it('generates correct payment hash', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => 'TEST_KEY',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_salt',
        'value'        => 'TEST_SALT',
        'channel_code' => 'default',
    ]);

    $txnid = 'TXN123';
    $amount = 100.50;
    $productInfo = 'Test Product';
    $firstname = 'John';
    $email = 'john@example.com';

    // Act
    $hash = $this->payU->generateHash($txnid, $amount, $productInfo, $firstname, $email);

    // Assert
    $expectedHashString = 'TEST_KEY|TXN123|100.5|Test Product|John|john@example.com|||||||||||TEST_SALT';
    $expectedHash = strtolower(hash('sha512', $expectedHashString));

    expect($hash)->toBe($expectedHash);
});

it('verifies hash from PayU response correctly', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => 'TEST_KEY',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_salt',
        'value'        => 'TEST_SALT',
        'channel_code' => 'default',
    ]);

    $response = [
        'status'      => 'success',
        'firstname'   => 'John',
        'amount'      => '100.50',
        'txnid'       => 'TXN123',
        'key'         => 'TEST_KEY',
        'productinfo' => 'Test Product',
        'email'       => 'john@example.com',
    ];

    $hashString = 'TEST_SALT|success|||||||||||john@example.com|John|Test Product|100.50|TXN123|TEST_KEY';
    $response['hash'] = strtolower(hash('sha512', $hashString));

    // Act
    $isValid = $this->payU->verifyHash($response);

    // Assert
    expect($isValid)->toBeTrue();
});

it('rejects invalid hash from PayU response', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_key',
        'value'        => 'TEST_KEY',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code'         => 'sales.payment_methods.payu.merchant_salt',
        'value'        => 'TEST_SALT',
        'channel_code' => 'default',
    ]);

    $response = [
        'status'      => 'success',
        'firstname'   => 'John',
        'amount'      => '100.50',
        'txnid'       => 'TXN123',
        'key'         => 'TEST_KEY',
        'productinfo' => 'Test Product',
        'email'       => 'john@example.com',
        'hash'        => 'invalid_hash_value',
    ];

    // Act
    $isValid = $this->payU->verifyHash($response);

    // Assert
    expect($isValid)->toBeFalse();
});

it('returns redirect URL for payment', function () {
    // Act
    $redirectUrl = $this->payU->getRedirectUrl();

    // Assert
    expect($redirectUrl)->toBe(route('payu.redirect'));
});
