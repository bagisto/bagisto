<?php

use Illuminate\Support\Facades\Http;
use Webkul\PayGlocal\Enums\PayGlocalPaymentStatus;
use Webkul\PayGlocal\Payment\PayGlocal;

/**
 * A real RSA keypair generated once per run, so the credential checks parse genuine keys without a
 * private key ever being checked into the repository.
 */
function payGlocalTestKeyPair(): array
{
    static $keys;

    if ($keys) {
        return $keys;
    }

    $resource = openssl_pkey_new([
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);

    openssl_pkey_export($resource, $privateKey);

    return $keys = [
        'public' => openssl_pkey_get_details($resource)['key'],
        'private' => $privateKey,
    ];
}

/**
 * The smallest thing `initiatePayment` will accept: it reads the totals, the currency and the
 * billing address off the cart, and a cart without an address simply sends no billing data.
 */
function payGlocalCartStub(): object
{
    return new class
    {
        public $id = 1;

        public $grand_total = 338.95;

        public $base_grand_total = 338.95;

        public $cart_currency_code = 'INR';

        public $base_currency_code = 'INR';

        public $billing_address = null;
    };
}

/**
 * Configure a complete, usable set of credentials, so that a test only has to say which single
 * value it wants broken.
 */
function configurePayGlocalCredentials(array $overrides = []): void
{
    $credentials = array_merge([
        'merchant_id' => 'test_merchant',
        'public_key_id' => 'test_public_kid',
        'private_key_id' => 'test_private_kid',
        'payglocal_public_key' => payGlocalTestKeyPair()['public'],
        'merchant_private_key' => payGlocalTestKeyPair()['private'],
        'accepted_currencies' => 'USD,INR',
    ], $overrides);

    foreach ($credentials as $field => $value) {
        test()->setConfig('sales.payment_methods.payglocal.'.$field, $value);
    }
}

beforeEach(function () {
    $this->payGlocal = app(PayGlocal::class);
});

// ============================================================================
// Configuration
// ============================================================================

it('should return the correct payment method code', function () {
    $code = $this->payGlocal->getCode();

    expect($code)->toBe('payglocal');
});

it('should return the payment method title from configuration', function () {
    $this->setConfig('sales.payment_methods.payglocal.title', 'PayGlocal Payment Gateway');

    $title = $this->payGlocal->getTitle();

    expect($title)->toBe('PayGlocal Payment Gateway');
});

it('should return the payment method description from configuration', function () {
    $this->setConfig('sales.payment_methods.payglocal.description', 'Pay securely using PayGlocal');

    $description = $this->payGlocal->getDescription();

    expect($description)->toBe('Pay securely using PayGlocal');
});

it('should return the merchant credentials from configuration', function () {
    $this->setConfig([
        'sales.payment_methods.payglocal.merchant_id' => 'test_merchant',
        'sales.payment_methods.payglocal.public_key_id' => 'test_public_kid',
        'sales.payment_methods.payglocal.private_key_id' => 'test_private_kid',
    ]);

    expect($this->payGlocal->getMerchantId())->toBe('test_merchant')
        ->and($this->payGlocal->getPublicKeyId())->toBe('test_public_kid')
        ->and($this->payGlocal->getPrivateKeyId())->toBe('test_private_kid');
});

it('should return the sandbox base url when sandbox mode is enabled', function () {
    $this->setConfig('sales.payment_methods.payglocal.sandbox', '1');

    $baseUrl = $this->payGlocal->getBaseUrl();

    expect($this->payGlocal->isSandbox())->toBeTrue()
        ->and($baseUrl)->toBe(PayGlocal::SANDBOX_URL);
});

it('should return the production base url when sandbox mode is disabled', function () {
    $this->setConfig('sales.payment_methods.payglocal.sandbox', '0');

    $baseUrl = $this->payGlocal->getBaseUrl();

    expect($this->payGlocal->isSandbox())->toBeFalse()
        ->and($baseUrl)->toBe(PayGlocal::PRODUCTION_URL);
});

it('should return the payment method image from configuration', function () {
    $this->setConfig('sales.payment_methods.payglocal.image', 'payglocal/custom-logo.png');

    $image = $this->payGlocal->getImage();

    expect($image)->toContain('payglocal/custom-logo.png');
});

it('should return the default payment method image when not configured', function () {
    $image = $this->payGlocal->getImage();

    expect($image)->toContain('payglocal')
        ->and($image)->toContain('.png');
});

it('should return the correct redirect URL', function () {
    $url = $this->payGlocal->getRedirectUrl();

    expect($url)->toBe(route('payglocal.redirect'));
});

// ============================================================================
// Credentials
// ============================================================================

it('should report the credentials valid when all are configured', function () {
    configurePayGlocalCredentials();

    $hasValidCredentials = $this->payGlocal->hasValidCredentials();

    expect($hasValidCredentials)->toBeTrue();
});

it('should report the credentials invalid when the merchant id is missing', function () {
    configurePayGlocalCredentials(['merchant_id' => '']);

    $hasValidCredentials = $this->payGlocal->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should report the credentials invalid when a key id is missing', function () {
    configurePayGlocalCredentials(['public_key_id' => '']);

    $hasValidCredentials = $this->payGlocal->hasValidCredentials();

    expect($hasValidCredentials)->toBeFalse();
});

it('should report the keys usable when they parse', function () {
    configurePayGlocalCredentials();

    $hasUsableKeys = $this->payGlocal->hasUsableKeys();

    expect($hasUsableKeys)->toBeTrue();
});

it('should report the keys unusable when they cannot be parsed', function () {
    configurePayGlocalCredentials([
        'payglocal_public_key' => 'not-a-real-pem',
        'merchant_private_key' => 'not-a-real-pem',
    ]);

    expect($this->payGlocal->hasValidCredentials())->toBeTrue()
        ->and($this->payGlocal->hasUsableKeys())->toBeFalse();
});

it('should report the keys unusable when they are missing', function () {
    configurePayGlocalCredentials([
        'payglocal_public_key' => '',
        'merchant_private_key' => '',
    ]);

    $hasUsableKeys = $this->payGlocal->hasUsableKeys();

    expect($hasUsableKeys)->toBeFalse();
});

// ============================================================================
// Availability And Currencies
// ============================================================================

it('should not be available when the credentials are missing', function () {
    $this->setConfig('sales.payment_methods.payglocal.active', '1');

    configurePayGlocalCredentials([
        'payglocal_public_key' => '',
        'merchant_private_key' => '',
    ]);

    $isAvailable = $this->payGlocal->isAvailable();

    expect($isAvailable)->toBeFalse();
});

it('should still be offered when the currency is not accepted', function () {
    $this->setConfig('sales.payment_methods.payglocal.active', '1');

    configurePayGlocalCredentials(['accepted_currencies' => 'EUR']);

    $isAvailable = $this->payGlocal->isAvailable();

    expect($isAvailable)->toBeTrue()
        ->and($this->payGlocal->isCurrencySupported('USD'))->toBeFalse();
});

it('should return the accepted currencies as a list', function () {
    $this->setConfig('sales.payment_methods.payglocal.accepted_currencies', 'USD, INR ,EUR');

    $currencies = $this->payGlocal->getAcceptedCurrencies();

    expect($currencies)->toBe(['USD', 'INR', 'EUR']);
});

it('should accept a currency regardless of case', function () {
    $this->setConfig('sales.payment_methods.payglocal.accepted_currencies', 'USD,INR');

    expect($this->payGlocal->isCurrencySupported('inr'))->toBeTrue()
        ->and($this->payGlocal->isCurrencySupported('USD'))->toBeTrue()
        ->and($this->payGlocal->isCurrencySupported('EUR'))->toBeFalse();
});

it('should charge in the store currency rather than the one the customer is browsing in', function () {
    $cart = new class
    {
        public $grand_total = 1.00;

        public $base_grand_total = 80.00;

        public $cart_currency_code = 'USD';

        public $base_currency_code = 'INR';
    };

    expect($this->payGlocal->getCurrency($cart))->toBe('INR');
});

// ============================================================================
// Gateway Requests
// ============================================================================

it('should read the redirect and status urls out of an initiated payment', function () {
    configurePayGlocalCredentials();

    Http::fake([
        '*/gl/v1/payments/initiate/paycollect' => Http::response([
            'gid' => 'gl_o-a1c803266de57f693f1k0lTX2',
            'status' => 'SENT_FOR_AUTHENTICATION',
            'message' => 'Transaction is sent for authentication',
            'data' => [
                'redirectUrl' => 'https://api.uat.pygcl.com/gl/payflow-ui/?x-gl-token=token',
                'statusUrl' => 'https://api.uat.pygcl.com/gl/v1/payments/gl_o-a1c803266de57f693f1k0lTX2/status?x-gl-token=token',
            ],
        ], 200),
    ]);

    $response = $this->payGlocal->initiatePayment(payGlocalCartStub(), 'PGL1TTEST');

    expect($response['gid'])->toBe('gl_o-a1c803266de57f693f1k0lTX2')
        ->and($response['redirectUrl'])->toBe('https://api.uat.pygcl.com/gl/payflow-ui/?x-gl-token=token')
        ->and($response['statusUrl'])->toContain('/status?x-gl-token=token');
});

it('should refuse an initiated payment that carries nowhere to send the customer', function () {
    configurePayGlocalCredentials();

    Http::fake([
        '*/gl/v1/payments/initiate/paycollect' => Http::response([
            'gid' => 'gl_o-test',
            'data' => [
                'statusUrl' => 'https://api.uat.pygcl.com/gl/v1/payments/gl_o-test/status?x-gl-token=token',
            ],
        ], 200),
    ]);

    $response = $this->payGlocal->initiatePayment(payGlocalCartStub(), 'PGL1TTEST');

    expect($response)->toBeNull();
});

it('should return null when PayGlocal rejects the initiate request', function () {
    configurePayGlocalCredentials();

    Http::fake([
        '*/gl/v1/payments/initiate/paycollect' => Http::response([
            'gid' => 'gl_a1c749eb78e03a33',
            'status' => 'REQUEST_ERROR',
            'message' => 'Authentication failed, please contact support',
        ], 401),
    ]);

    $response = $this->payGlocal->initiatePayment(payGlocalCartStub(), 'PGL1TTEST');

    expect($response)->toBeNull();
});

it('should read the payment status out of the status api', function () {
    Http::fake([
        '*/status*' => Http::response([
            'gid' => 'gl_a1c7fa4ddc487f1cf25uut0lTX2',
            'status' => 'SENT_FOR_CAPTURE',
            'message' => 'Transaction is sent_for_capture',
            'reasonCode' => 'GL-201-001',
            'data' => [
                'gid' => 'gl_o-a1c7fa4ddc487f1cfuut0lTX2',
                'status' => 'SENT_FOR_CAPTURE',
                'Amount' => '338.95',
                'txnCurrency' => 'INR',
                'merchantTxnId' => 'PGL39T3CEV9U2URZ',
            ],
            'errors' => null,
        ], 200),
    ]);

    $response = $this->payGlocal->getTransactionStatus('https://api.uat.pygcl.com/gl/v1/payments/gl_o-test/status?x-gl-token=token');

    expect($response['status'])->toBe('SENT_FOR_CAPTURE')
        ->and($response['data']['txnCurrency'])->toBe('INR')
        ->and(PayGlocalPaymentStatus::tryFrom($response['status'])->isSuccessful())->toBeTrue();
});

it('should return null when the status api cannot be read', function () {
    Http::fake([
        '*/status*' => Http::response([
            'gid' => 'gl_a1c6e3ecc919bb2c',
            'status' => 'REQUEST_ERROR',
            'message' => 'Authentication failed, please contact support',
        ], 401),
    ]);

    $response = $this->payGlocal->getTransactionStatus('https://api.uat.pygcl.com/gl/v1/payments/gl_o-test/status');

    expect($response)->toBeNull();
});

it('should return null without calling PayGlocal when there is no status url', function () {
    Http::fake();

    $response = $this->payGlocal->getTransactionStatus(null);

    expect($response)->toBeNull();

    Http::assertNothingSent();
});

// ============================================================================
// Transaction References
// ============================================================================

it('should generate a merchant transaction id carrying the cart id', function () {
    $merchantTxnId = $this->payGlocal->generateMerchantTxnId(42);

    expect($merchantTxnId)->toStartWith('PGL42T')
        ->and($merchantTxnId)->not->toBe($this->payGlocal->generateMerchantTxnId(42));
});

it('should read the cart out of a merchant transaction id', function () {
    expect($this->payGlocal->parseCartId('PGL28TPQWIXAHJ3F'))->toBe(28)
        ->and($this->payGlocal->parseCartId('nonsense'))->toBeNull()
        ->and($this->payGlocal->parseCartId(null))->toBeNull();
});

it('should read the captured amount and currency out of what PayGlocal reports', function () {
    $statusBody = ['data' => ['Amount' => '42.99', 'txnCurrency' => 'inr']];

    $claims = ['Amount' => '42.99', 'merchantTxnId' => 'PGL28TPQWIXAHJ3F'];

    expect($this->payGlocal->getCapturedAmount($statusBody))->toBe(42.99)
        ->and($this->payGlocal->getCapturedCurrency($statusBody))->toBe('INR')
        ->and($this->payGlocal->getCapturedAmount($claims))->toBe(42.99)
        ->and($this->payGlocal->getReportedMerchantTxnId($claims))->toBe('PGL28TPQWIXAHJ3F');
});
