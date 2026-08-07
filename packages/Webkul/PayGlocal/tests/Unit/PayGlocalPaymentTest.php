<?php

use Illuminate\Support\Facades\Http;
use Webkul\Core\Models\CoreConfig;
use Webkul\PayGlocal\Enums\PayGlocalPaymentStatus;
use Webkul\PayGlocal\Payment\PayGlocal;

/**
 * Real RSA keys, just not PayGlocal's. The payment method parses whatever is configured and
 * refuses to report itself available if the keys cannot be loaded, so a dummy string would not
 * exercise the credential checks.
 */
const TEST_PUBLIC_KEY = <<<'PEM'
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwjdww8ih6ntAYIyTAJuw
xzPMk1M2Ca7jIX/F3c0ozcwHy2k1SgA4fFZ7wTgj1JnFANQvVDD2UBydXHbeigoT
6xuPEBefxi3Vt/qStkwQ4dpp6DbOI0tzQ9GlOb06kugAc0knQTauA661nDmHiqHq
UVefDhQ7X4r+kU9PYh49UJJsnZzV+8lTLeM8/tpiHlJS9KeyY4+Z6MfF9Ww/OEbl
5jS3C+u52DiWBOGPFNdwTWwJ3zGgWim8XqD0GK++GM3ljKLLA60owptiRru6b9f/
1bhEeyZ9D3OQwecR9u+Lp1IsoCHI313gGJ8QCUdtWkgzvd2yYZIpcqcFFXF7nNzd
xQIDAQAB
-----END PUBLIC KEY-----
PEM;

const TEST_PRIVATE_KEY = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDCN3DDyKHqe0Bg
jJMAm7DHM8yTUzYJruMhf8XdzSjNzAfLaTVKADh8VnvBOCPUmcUA1C9UMPZQHJ1c
dt6KChPrG48QF5/GLdW3+pK2TBDh2mnoNs4jS3ND0aU5vTqS6ABzSSdBNq4DrrWc
OYeKoepRV58OFDtfiv6RT09iHj1QkmydnNX7yVMt4zz+2mIeUlL0p7Jjj5nox8X1
bD84RuXmNLcL67nYOJYE4Y8U13BNbAnfMaBaKbxeoPQYr74YzeWMossDrSjCm2JG
u7pv1//VuER7Jn0Pc5DB5xH274unUiygIcjfXeAYnxAJR21aSDO93bJhkilypwUV
cXuc3N3FAgMBAAECggEAGBZohHJo7obXHwJgUGDUOyiSqf+rLJFFt0e8bSa+9aM6
oOuirH5m2GOg2VUn+ya6dxYElrRfNY65pO2olRtB3tTIrg4YBqwe8mtVRd8c8D4M
COe/MkPEfyKhvjhYwD6eS64aJxhBMHtl0oM/ax4WIdI0YQ/OUKpm/0kEXJ7JK6mo
eaBUp4oMrvZa0+1h7fjyc0ZU1z9arToTReW0BCc2sE4tZuEAbR+QqNRE5DgWlWcu
0yXg62uMPdFKkROLgOag+9pqq9jneF/yz7kZxshYmpilxxe0CSKgOTONiZS7cgvk
wAk0kY6iBkzgsPyvkWswg+NxfklPH6nJPpOHzCSkIwKBgQDJeGAtUUFWxY4iP/6o
ZSPjNc1P+Rr8P5f1NIwYsqz1Y5axALfYCqSn9dzxv2FjI5guNxSnemzl781Q28uE
FK1IDI5AyFk1VrqB2daPHPZdbXn7GhiyQQUAs7C127Ubd6KKX/k6yWYM1YO401Be
ty9qM2cDZRkmXXEj0ICX10cTjwKBgQD2yHfTHD0tZfa9hU3mn+kDNehJOObfhT9x
QpsgMMmUz4s23XlvcHI4jczBeaZnMX0B6x+vE3hLEz9cYfE+AdsGRTfrRszmJdXO
LyKsrzIikFmc5bvyhEVVnFerTtCEr67qchHOicU2++YTsbLc1DzAem2yAKbuENc3
2BLbNHC/awKBgQCPCKc/hTCeKiN+rXBenW+dH9Vjsbc23u9DZssPvcqNbObPQ3NC
Lkw38pWqC/VYLS0don1HaeNmW5mojmMuon9jZ4aW96Zd9/Txu3ZYpHdEXTT80Mo3
w3GJzgjnE9TAa286TmjjE5kgA3ZBAcVNeUBwZY39Gwl/81cf1id1paEQgQKBgQCF
ochA6Om3y57wwV6No8npkydVfxqFrwHLsuWNaN2/VyNrckJvtdQkC6T0n4scFhA7
GbbudvyMqr+EpwSbLyYLHzBIlu4dMh+0ppGAMN5VGRVtgHlluXpSAXb3rJX9Q6TU
DzDVRoUkYQMVZwQT0FmVYLZFzVSXVUc1Vivfx2XGQQKBgD/rnhOVxDhyp159gPdt
nwI8qMfyG6f0cMwtNKqHRc1DGirSl7JbMNMR2cQGNbR5S00GKz0jEdsDyl8xew8M
GYIy6CAUGfqz2uAwdJns3z61GIUfDnhZMPC8YjdziQ5O+wZAbPkrO3WRxWzI4uMo
VFjL8elyuuif9hBG6TzNorUy
-----END PRIVATE KEY-----
PEM;

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
    // Without a redirect url there is no hosted checkout to reach, so it must not be started.
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
    configureCredentials();

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
    $response = $this->payGlocal->getTransactionStatus('gl_o-test');

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

        public $cart_currency_code = 'INR';

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
        'payglocal_public_key' => TEST_PUBLIC_KEY,
        'merchant_private_key' => TEST_PRIVATE_KEY,
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
