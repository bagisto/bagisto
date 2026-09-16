<?php

use Webkul\PayU\Payment\PayU;

beforeEach(function () {
    $this->payU = app(PayU::class);
});

// ============================================================================
// Configuration
// ============================================================================

it('should return the correct payment method code', function () {
    $code = $this->payU->getCode();

    expect($code)->toBe('payu');
});

it('should return the payment method title from configuration', function () {
    $this->setConfig('sales.payment_methods.payu.title', 'PayU Payment Gateway');

    $title = $this->payU->getTitle();

    expect($title)->toBe('PayU Payment Gateway');
});

it('should return the payment method description from configuration', function () {
    $this->setConfig('sales.payment_methods.payu.description', 'Pay securely using PayU');

    $description = $this->payU->getDescription();

    expect($description)->toBe('Pay securely using PayU');
});

it('should return the merchant key from configuration', function () {
    $this->setConfig('sales.payment_methods.payu.merchant_key', 'test_merchant_key_123');

    $merchantKey = $this->payU->getMerchantKey();

    expect($merchantKey)->toBe('test_merchant_key_123');
});

it('should return the merchant salt from configuration', function () {
    $this->setConfig('sales.payment_methods.payu.merchant_salt', 'test_merchant_salt_456');

    $merchantSalt = $this->payU->getMerchantSalt();

    expect($merchantSalt)->toBe('test_merchant_salt_456');
});

it('should return the payment method image from configuration', function () {
    $this->setConfig('sales.payment_methods.payu.image', 'payu/custom-logo.png');

    $image = $this->payU->getImage();

    expect($image)->toContain('payu/custom-logo.png');
});

it('should return the default payment method image when not configured', function () {
    $image = $this->payU->getImage();

    expect($image)->toContain('payu')
        ->and($image)->toContain('.png');
});

it('should return the redirect URL for the payment', function () {
    $redirectUrl = $this->payU->getRedirectUrl();

    expect($redirectUrl)->toBe(route('payu.redirect'));
});

// ============================================================================
// Sandbox Mode
// ============================================================================

it('should report sandbox mode as enabled', function () {
    $this->setConfig('sales.payment_methods.payu.sandbox', '1');

    $isSandbox = $this->payU->isSandbox();

    expect($isSandbox)->toBeTrue();
});

it('should report sandbox mode as disabled', function () {
    $this->setConfig('sales.payment_methods.payu.sandbox', '0');

    $isSandbox = $this->payU->isSandbox();

    expect($isSandbox)->toBeFalse();
});

it('should return the sandbox payment URL when sandbox is enabled', function () {
    $this->setConfig('sales.payment_methods.payu.sandbox', '1');

    $paymentUrl = $this->payU->getPaymentUrl();

    expect($paymentUrl)->toBe('https://test.payu.in/_payment');
});

it('should return the production payment URL when sandbox is disabled', function () {
    $this->setConfig('sales.payment_methods.payu.sandbox', '0');

    $paymentUrl = $this->payU->getPaymentUrl();

    expect($paymentUrl)->toBe('https://secure.payu.in/_payment');
});

// ============================================================================
// Credentials
// ============================================================================

it('should report the credentials as valid', function () {
    $this->setConfig([
        'sales.payment_methods.payu.merchant_key' => 'test_key',
        'sales.payment_methods.payu.merchant_salt' => 'test_salt',
    ]);

    $hasValidCredentials = $this->payU->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('should report the credentials invalid when the merchant key is missing', function () {
    $this->setConfig([
        'sales.payment_methods.payu.merchant_key' => '',
        'sales.payment_methods.payu.merchant_salt' => 'test_salt',
    ]);

    $hasValidCredentials = $this->payU->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should report the credentials invalid when the merchant salt is missing', function () {
    $this->setConfig([
        'sales.payment_methods.payu.merchant_key' => 'test_key',
        'sales.payment_methods.payu.merchant_salt' => '',
    ]);

    $hasValidCredentials = $this->payU->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should not be available when the credentials are invalid', function () {
    $this->setConfig([
        'sales.payment_methods.payu.active' => '1',
        'sales.payment_methods.payu.merchant_key' => '',
    ]);

    $isAvailable = $this->payU->isAvailable();

    expect($isAvailable)->toBeFalse();
});

// ============================================================================
// Hashing
// ============================================================================

it('should generate the correct payment hash', function () {
    $this->setConfig([
        'sales.payment_methods.payu.merchant_key' => 'TEST_KEY',
        'sales.payment_methods.payu.merchant_salt' => 'TEST_SALT',
    ]);

    $txnid = 'TXN123';
    $amount = 100.50;
    $productInfo = 'Test Product';
    $firstname = 'John';
    $email = 'john@example.com';
    $udf1 = '456';

    $hash = $this->payU->generateHash($txnid, $amount, $productInfo, $firstname, $email, $udf1);

    $expectedHashString = 'TEST_KEY|TXN123|100.5|Test Product|John|john@example.com|456||||||||||TEST_SALT';

    $expectedHash = strtolower(hash('sha512', $expectedHashString));

    expect($hash)->toBe($expectedHash);
});

it('should verify the hash of a PayU response', function () {
    $this->setConfig([
        'sales.payment_methods.payu.merchant_key' => 'TEST_KEY',
        'sales.payment_methods.payu.merchant_salt' => 'TEST_SALT',
    ]);

    $response = [
        'status' => 'success',
        'firstname' => 'John',
        'amount' => '100.50',
        'txnid' => 'TXN123',
        'key' => 'TEST_KEY',
        'productinfo' => 'Test Product',
        'email' => 'john@example.com',
        'udf1' => '456',
    ];

    $hashString = 'TEST_SALT|success||||||||||456|john@example.com|John|Test Product|100.50|TXN123|TEST_KEY';

    $response['hash'] = strtolower(hash('sha512', $hashString));

    $isValid = $this->payU->verifyHash($response);

    expect($isValid)->toBeTrue();
});

it('should reject an invalid hash in a PayU response', function () {
    $this->setConfig([
        'sales.payment_methods.payu.merchant_key' => 'TEST_KEY',
        'sales.payment_methods.payu.merchant_salt' => 'TEST_SALT',
    ]);

    $response = [
        'status' => 'success',
        'firstname' => 'John',
        'amount' => '100.50',
        'txnid' => 'TXN123',
        'key' => 'TEST_KEY',
        'productinfo' => 'Test Product',
        'email' => 'john@example.com',
        'udf1' => '456',
        'hash' => 'invalid_hash_value',
    ];

    $isValid = $this->payU->verifyHash($response);

    expect($isValid)->toBeFalse();
});
