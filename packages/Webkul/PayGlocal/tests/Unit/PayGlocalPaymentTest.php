<?php

use Illuminate\Support\Facades\Http;
use Webkul\Core\Models\CoreConfig;
use Webkul\PayGlocal\Enums\PayGlocalPaymentStatus;
use Webkul\PayGlocal\Payment\PayGlocal;

/**
 * A real RSA keypair, generated per run rather than written into the file. The payment method
 * parses whatever is configured and refuses to report itself available when the keys cannot be
 * loaded, so a dummy string would not exercise the credential checks - but a private key checked
 * into the repository is a private key checked into the repository, whatever it unlocks.
 */
function testKeyPair(): array
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

beforeEach(function () {
    $this->payGlocal = app(PayGlocal::class);
});

it('returns the correct payment method code', function () {
    // Act
    $code = $this->payGlocal->getCode();

    // Assert
    expect($code)->toBe('payglocal');
});

it('returns the payment method title from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.title',
        'value' => 'PayGlocal Payment Gateway',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    // Act
    $title = $this->payGlocal->getTitle();

    // Assert
    expect($title)->toBe('PayGlocal Payment Gateway');
});

it('returns the payment method description from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.description',
        'value' => 'Pay securely using PayGlocal',
        'channel_code' => 'default',
        'locale_code' => 'en',
    ]);

    // Act
    $description = $this->payGlocal->getDescription();

    // Assert
    expect($description)->toBe('Pay securely using PayGlocal');
});

it('returns the merchant credentials from configuration', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.merchant_id',
        'value' => 'test_merchant',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.public_key_id',
        'value' => 'test_public_kid',
        'channel_code' => 'default',
    ]);

    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.private_key_id',
        'value' => 'test_private_kid',
        'channel_code' => 'default',
    ]);

    // Act & Assert
    expect($this->payGlocal->getMerchantId())->toBe('test_merchant')
        ->and($this->payGlocal->getPublicKeyId())->toBe('test_public_kid')
        ->and($this->payGlocal->getPrivateKeyId())->toBe('test_private_kid');
});

it('returns the sandbox base url when sandbox mode is enabled', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.sandbox',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    // Act
    $baseUrl = $this->payGlocal->getBaseUrl();

    // Assert
    expect($this->payGlocal->isSandbox())->toBeTrue()
        ->and($baseUrl)->toBe(PayGlocal::SANDBOX_URL);
});

it('returns the production base url when sandbox mode is disabled', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.sandbox',
        'value' => '0',
        'channel_code' => 'default',
    ]);

    // Act
    $baseUrl = $this->payGlocal->getBaseUrl();

    // Assert
    expect($this->payGlocal->isSandbox())->toBeFalse()
        ->and($baseUrl)->toBe(PayGlocal::PRODUCTION_URL);
});

it('checks if credentials are valid when all are configured', function () {
    // Arrange
    configureCredentials();

    // Act
    $hasValidCredentials = $this->payGlocal->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeTrue();
});

it('returns false if the merchant id is missing', function () {
    // Arrange
    configureCredentials(['merchant_id' => '']);

    // Act
    $hasValidCredentials = $this->payGlocal->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeFalse();
});

it('returns false if a key id is missing', function () {
    // Arrange
    configureCredentials(['public_key_id' => '']);

    // Act
    $hasValidCredentials = $this->payGlocal->hasValidCredentials();

    // Assert
    expect($hasValidCredentials)->toBeFalse();
});

it('reports the keys usable when they parse', function () {
    // Arrange
    configureCredentials();

    // Act
    $hasUsableKeys = $this->payGlocal->hasUsableKeys();

    // Assert
    expect($hasUsableKeys)->toBeTrue();
});

it('reports the keys unusable when they cannot be parsed', function () {
    // Arrange
    configureCredentials([
        'payglocal_public_key' => 'not-a-real-pem',
        'merchant_private_key' => 'not-a-real-pem',
    ]);

    // Act & Assert
    // Present, so the method is still offered, but unusable, so a payment cannot be started.
    expect($this->payGlocal->hasValidCredentials())->toBeTrue()
        ->and($this->payGlocal->hasUsableKeys())->toBeFalse();
});

it('reports the keys unusable when they are missing', function () {
    // Arrange
    configureCredentials([
        'payglocal_public_key' => '',
        'merchant_private_key' => '',
    ]);

    // Act
    $hasUsableKeys = $this->payGlocal->hasUsableKeys();

    // Assert
    expect($hasUsableKeys)->toBeFalse();
});

it('returns the accepted currencies as a list', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.accepted_currencies',
        'value' => 'USD, INR ,EUR',
        'channel_code' => 'default',
    ]);

    // Act
    $currencies = $this->payGlocal->getAcceptedCurrencies();

    // Assert
    expect($currencies)->toBe(['USD', 'INR', 'EUR']);
});

it('checks whether a currency is accepted regardless of case', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.accepted_currencies',
        'value' => 'USD,INR',
        'channel_code' => 'default',
    ]);

    // Act & Assert
    expect($this->payGlocal->isCurrencySupported('inr'))->toBeTrue()
        ->and($this->payGlocal->isCurrencySupported('USD'))->toBeTrue()
        ->and($this->payGlocal->isCurrencySupported('EUR'))->toBeFalse();
});

it('is not available when credentials are missing', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    configureCredentials([
        'payglocal_public_key' => '',
        'merchant_private_key' => '',
    ]);

    // Act
    $isAvailable = $this->payGlocal->isAvailable();

    // Assert
    expect($isAvailable)->toBeFalse();
});

it('is still offered when the currency is not accepted', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.active',
        'value' => '1',
        'channel_code' => 'default',
    ]);

    configureCredentials(['accepted_currencies' => 'EUR']);

    // Act
    $isAvailable = $this->payGlocal->isAvailable();

    // Assert
    // Availability turns on the credentials alone. An unsupported currency is caught when the
    // payment is started, so the customer is told why rather than finding the method missing.
    expect($isAvailable)->toBeTrue()
        ->and($this->payGlocal->isCurrencySupported('USD'))->toBeFalse();
});

it('generates a merchant transaction id carrying the cart id', function () {
    // Act
    $merchantTxnId = $this->payGlocal->generateMerchantTxnId(42);

    // Assert
    expect($merchantTxnId)->toStartWith('PGL42T')
        ->and($merchantTxnId)->not->toBe($this->payGlocal->generateMerchantTxnId(42));
});

it('returns payment method image from config', function () {
    // Arrange
    CoreConfig::factory()->create([
        'code' => 'sales.payment_methods.payglocal.image',
        'value' => 'payglocal/custom-logo.png',
        'channel_code' => 'default',
    ]);

    // Act
    $image = $this->payGlocal->getImage();

    // Assert
    expect($image)->toContain('payglocal/custom-logo.png');
});

it('returns default payment method image when not configured', function () {
    // Act
    $image = $this->payGlocal->getImage();

    // Assert
    // The image path includes a Vite asset hash, so just check it contains the filename
    expect($image)->toContain('payglocal')
        ->and($image)->toContain('.png');
});

it('returns the correct redirect URL', function () {
    // Act
    $url = $this->payGlocal->getRedirectUrl();

    // Assert
    expect($url)->toBe(route('payglocal.redirect'));
});

it('reads the redirect and status urls out of an initiated payment', function () {
    // Arrange
    configureCredentials();

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

    // Act
    $response = $this->payGlocal->initiatePayment(cartStub(), 'PGL1TTEST');

    // Assert
    expect($response['gid'])->toBe('gl_o-a1c803266de57f693f1k0lTX2')
        ->and($response['redirectUrl'])->toBe('https://api.uat.pygcl.com/gl/payflow-ui/?x-gl-token=token')
        ->and($response['statusUrl'])->toContain('/status?x-gl-token=token');
});

it('refuses an initiated payment that carries nowhere to send the customer', function () {
    // Arrange
    // The status url arrives later, with the token PayGlocal signs, so only somewhere to send
    // the customer is needed to start. Without that there is no hosted checkout to reach.
    configureCredentials();

    Http::fake([
        '*/gl/v1/payments/initiate/paycollect' => Http::response([
            'gid' => 'gl_o-test',
            'data' => [
                'statusUrl' => 'https://api.uat.pygcl.com/gl/v1/payments/gl_o-test/status?x-gl-token=token',
            ],
        ], 200),
    ]);

    // Act
    $response = $this->payGlocal->initiatePayment(cartStub(), 'PGL1TTEST');

    // Assert
    expect($response)->toBeNull();
});

it('returns null when payglocal rejects the initiate request', function () {
    // Arrange
    configureCredentials();

    Http::fake([
        '*/gl/v1/payments/initiate/paycollect' => Http::response([
            'gid' => 'gl_a1c749eb78e03a33',
            'status' => 'REQUEST_ERROR',
            'message' => 'Authentication failed, please contact support',
        ], 401),
    ]);

    // Act
    $response = $this->payGlocal->initiatePayment(cartStub(), 'PGL1TTEST');

    // Assert
    expect($response)->toBeNull();
});

it('reads the payment status out of the status api', function () {
    // Arrange
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

    // Act
    $response = $this->payGlocal->getTransactionStatus('https://api.uat.pygcl.com/gl/v1/payments/gl_o-test/status?x-gl-token=token');

    // Assert
    expect($response['status'])->toBe('SENT_FOR_CAPTURE')
        ->and($response['data']['txnCurrency'])->toBe('INR')
        ->and(PayGlocalPaymentStatus::tryFrom($response['status'])->isSuccessful())->toBeTrue();
});

it('returns null when the status api cannot be read', function () {
    // Arrange
    Http::fake([
        '*/status*' => Http::response([
            'gid' => 'gl_a1c6e3ecc919bb2c',
            'status' => 'REQUEST_ERROR',
            'message' => 'Authentication failed, please contact support',
        ], 401),
    ]);

    // Act
    $response = $this->payGlocal->getTransactionStatus('https://api.uat.pygcl.com/gl/v1/payments/gl_o-test/status');

    // Assert
    // Null is not a failed payment: the caller leaves the attempt pending for the webhook.
    expect($response)->toBeNull();
});

it('returns null without calling payglocal when there is no status url', function () {
    // Arrange
    Http::fake();

    // Act
    $response = $this->payGlocal->getTransactionStatus(null);

    // Assert
    expect($response)->toBeNull();

    Http::assertNothingSent();
});

/**
 * The smallest thing `initiatePayment` will accept: it reads the totals, the currency and the
 * billing address off the cart, and a cart without an address simply sends no billing data.
 */
function cartStub(): object
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
function configureCredentials(array $overrides = []): void
{
    $credentials = array_merge([
        'merchant_id' => 'test_merchant',
        'public_key_id' => 'test_public_kid',
        'private_key_id' => 'test_private_kid',
        'payglocal_public_key' => testKeyPair()['public'],
        'merchant_private_key' => testKeyPair()['private'],
        'accepted_currencies' => 'USD,INR',
    ], $overrides);

    foreach ($credentials as $field => $value) {
        CoreConfig::factory()->create([
            'code' => 'sales.payment_methods.payglocal.'.$field,
            'value' => $value,
            'channel_code' => 'default',
        ]);
    }
}

it('reads the cart out of a merchant transaction id', function () {
    expect($this->payGlocal->parseCartId('PGL28TPQWIXAHJ3F'))->toBe(28)
        ->and($this->payGlocal->parseCartId('nonsense'))->toBeNull()
        ->and($this->payGlocal->parseCartId(null))->toBeNull();
});

it('reads the captured amount and currency out of what payglocal reports', function () {
    // The shape PayGlocal really answers the status call with.
    $statusBody = ['data' => ['Amount' => '42.99', 'txnCurrency' => 'inr']];

    expect($this->payGlocal->getCapturedAmount($statusBody))->toBe(42.99)
        ->and($this->payGlocal->getCapturedCurrency($statusBody))->toBe('INR');

    // The shape of the claims PayGlocal signs into the callback token.
    $claims = ['Amount' => '42.99', 'merchantTxnId' => 'PGL28TPQWIXAHJ3F'];

    expect($this->payGlocal->getCapturedAmount($claims))->toBe(42.99)
        ->and($this->payGlocal->getReportedMerchantTxnId($claims))->toBe('PGL28TPQWIXAHJ3F');
});

it('charges in the store currency rather than the one the customer is browsing in', function () {
    // A cart shown as $1 against an INR store is charged as the base total, in INR.
    $cart = new class
    {
        public $grand_total = 1.00;

        public $base_grand_total = 80.00;

        public $cart_currency_code = 'USD';

        public $base_currency_code = 'INR';
    };

    expect($this->payGlocal->getCurrency($cart))->toBe('INR');
});
