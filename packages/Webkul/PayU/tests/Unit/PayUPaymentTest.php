<?php

use Webkul\Core\Models\CoreConfig;
use Webkul\PayU\Payment\PayU;

beforeEach(function () {
    $this->payU = app(PayU::class);
});

it('returns the correct payment method code', function () {
    $code = $this->payU->getCode();

    expect($code)->toBe('payu');
});

it('returns the payment method title from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.title',
        'value' => 'PayU Payment Gateway',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    $title = $this->payU->getTitle();

    expect($title)->toBe('PayU Payment Gateway');
});

it('returns the payment method description from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.description',
        'value' => 'Pay securely using PayU',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    $description = $this->payU->getDescription();

    expect($description)->toBe('Pay securely using PayU');
});

it('returns the merchant key from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => 'test_merchant_key_123',
        'channel_code' => 'default',
    ]);

    $merchantKey = $this->payU->getMerchantKey();

    expect($merchantKey)->toBe('test_merchant_key_123');
});

it('returns the merchant salt from configuration', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_salt',
        'value' => 'test_merchant_salt_456',
        'channel_code' => 'default',
    ]);

    $merchantSalt = $this->payU->getMerchantSalt();

    expect($merchantSalt)->toBe('test_merchant_salt_456');
});

it('checks if sandbox mode is enabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    $isSandbox = $this->payU->isSandbox();

    expect($isSandbox)->toBeTrue();
});

it('checks if sandbox mode is disabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    $isSandbox = $this->payU->isSandbox();

    expect($isSandbox)->toBeFalse();
});

it('returns sandbox payment URL when sandbox is enabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    $paymentUrl = $this->payU->getPaymentUrl();

    expect($paymentUrl)->toBe('https://test.payu.in/_payment');
});

it('returns production payment URL when sandbox is disabled', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    $paymentUrl = $this->payU->getPaymentUrl();

    expect($paymentUrl)->toBe('https://secure.payu.in/_payment');
});

it('checks if credentials are valid', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_salt',
        'value' => 'test_salt',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->payU->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('returns false if merchant key is missing', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_salt',
        'value' => 'test_salt',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->payU->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('returns false if merchant salt is missing', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => 'test_key',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_salt',
        'value' => '',
        'channel_code' => 'default',
    ]);

    $hasValidCredentials = $this->payU->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('is not available when credentials are invalid', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => '',
        'channel_code' => 'default',
    ]);

    $isAvailable = $this->payU->isAvailable();

    expect($isAvailable)->toBeFalse();
});

it('returns payment method image from config', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.image',
        'value' => 'payu/custom-logo.png',
        'channel_code' => 'default',
    ]);

    $image = $this->payU->getImage();

    expect($image)->toContain('payu/custom-logo.png');
});

it('returns default payment method image when not configured', function () {
    $image = $this->payU->getImage();

    expect($image)->toContain('payu')
        ->and($image)->toContain('.png');
});

it('generates correct payment hash', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => 'TEST_KEY',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_salt',
        'value' => 'TEST_SALT',
        'channel_code' => 'default',
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

it('verifies hash from PayU response correctly', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => 'TEST_KEY',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_salt',
        'value' => 'TEST_SALT',
        'channel_code' => 'default',
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

it('rejects invalid hash from PayU response', function () {
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_key',
        'value' => 'TEST_KEY',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payu.merchant_salt',
        'value' => 'TEST_SALT',
        'channel_code' => 'default',
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

it('returns redirect URL for payment', function () {
    $redirectUrl = $this->payU->getRedirectUrl();

    expect($redirectUrl)->toBe(route('payu.redirect'));
});
