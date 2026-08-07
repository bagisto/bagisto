<?php

namespace Webkul\PayGlocal\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\PayGlocal\Helpers\Crypto;
use Webkul\Payment\Payment\Payment;

class PayGlocal extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'payglocal';

    /**
     * PayGlocal's sandbox host.
     */
    const SANDBOX_URL = 'https://api.uat.payglocal.in';

    /**
     * PayGlocal's production host.
     */
    const PRODUCTION_URL = 'https://api.prod.payglocal.in';

    /**
     * Create a new payment method instance.
     *
     * @return void
     */
    public function __construct(protected Crypto $crypto) {}

    /**
     * Get redirect URL for PayGlocal payment.
     *
     * Returning a non-null URL is what makes the checkout hand control to this package:
     * the core one page checkout skips order creation and sends the customer here instead.
     */
    public function getRedirectUrl(): string
    {
        return route('payglocal.redirect');
    }

    /**
     * Check if payment method is available.
     */
    public function isAvailable(): bool
    {
        return parent::isAvailable() && $this->hasValidCredentials();
    }

    /**
     * Get the currencies the merchant's PayGlocal account accepts.
     *
     * The merchant types these in as a comma separated list of currency codes.
     */
    public function getAcceptedCurrencies(): array
    {
        return array_values(array_filter(array_map(
            'trim',
            explode(',', (string) $this->getConfigData('accepted_currencies'))
        )));
    }

    /**
     * Check whether PayGlocal accepts the given currency for this merchant.
     */
    public function isCurrencySupported(string $currency): bool
    {
        return in_array(
            strtoupper($currency),
            array_map('strtoupper', $this->getAcceptedCurrencies()),
            true
        );
    }

    /**
     * Get payment method title.
     */
    public function getTitle(): string
    {
        return $this->getConfigData('title') ?? trans('payglocal::app.title');
    }

    /**
     * Get payment method description.
     */
    public function getDescription(): string
    {
        return $this->getConfigData('description') ?? trans('payglocal::app.description');
    }

    /**
     * Get payment method image.
     */
    public function getImage(): string
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/payglocal.png', 'shop');
    }

    /**
     * Get the merchant id.
     */
    public function getMerchantId(): ?string
    {
        return $this->getConfigData('merchant_id');
    }

    /**
     * Get the key id of PayGlocal's public key.
     */
    public function getPublicKeyId(): ?string
    {
        return $this->getConfigData('public_key_id');
    }

    /**
     * Get the key id of the merchant's private key.
     */
    public function getPrivateKeyId(): ?string
    {
        return $this->getConfigData('private_key_id');
    }

    /**
     * Get PayGlocal's public key, used to encrypt requests and verify PayGlocal's tokens.
     */
    public function getPayGlocalPublicKey(): ?string
    {
        return $this->getConfigData('payglocal_public_key');
    }

    /**
     * Get the merchant's private key, used to sign requests.
     */
    public function getMerchantPrivateKey(): ?string
    {
        return $this->getConfigData('merchant_private_key');
    }

    /**
     * Check if sandbox mode is enabled.
     */
    public function isSandbox(): bool
    {
        return (bool) $this->getConfigData('sandbox');
    }

    /**
     * Get the PayGlocal host for the configured environment.
     */
    public function getBaseUrl(): string
    {
        return $this->isSandbox()
            ? self::SANDBOX_URL
            : self::PRODUCTION_URL;
    }

    /**
     * Check if all required credentials are configured.
     */
    public function hasValidCredentials(): bool
    {
        return ! empty($this->getMerchantId())
            && ! empty($this->getPublicKeyId())
            && ! empty($this->getPrivateKeyId())
            && ! empty($this->getPayGlocalPublicKey())
            && ! empty($this->getMerchantPrivateKey());
    }

    /**
     * Check that the configured keys actually parse.
     *
     * Asked when a payment is started rather than when the method is listed, so that an
     * unusable key produces an error the customer can see rather than a missing method.
     */
    public function hasUsableKeys(): bool
    {
        $payGlocalPublicKey = $this->getPayGlocalPublicKey();

        $merchantPrivateKey = $this->getMerchantPrivateKey();

        if (
            empty($payGlocalPublicKey)
            || empty($merchantPrivateKey)
        ) {
            return false;
        }

        return openssl_pkey_get_public($payGlocalPublicKey) !== false
            && openssl_pkey_get_private($merchantPrivateKey) !== false;
    }

    /**
     * Generate a merchant transaction id.
     */
    public function generateMerchantTxnId(int $cartId): string
    {
        return 'PGL'.$cartId.'T'.Str::upper(Str::random(10));
    }

    /**
     * Read the cart back out of a merchant transaction id.
     *
     * The cart is carried in the reference PayGlocal signs and echoes back, which is how the
     * other gateways find their way back to a cart without keeping a table of their own.
     */
    public function parseCartId(?string $merchantTxnId): ?int
    {
        if (! preg_match('/^PGL(\d+)T/', (string) $merchantTxnId, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    /**
     * Read the merchant transaction id back out of a status response or set of claims.
     */
    public function getReportedMerchantTxnId(?array $response): ?string
    {
        return $response['data']['merchantTxnId'] ?? $response['merchantTxnId'] ?? null;
    }

    /**
     * Read the amount PayGlocal reports having taken, when it reports one.
     */
    public function getCapturedAmount(?array $response): ?float
    {
        foreach (['Amount', 'amount', 'totalAmount'] as $key) {
            $amount = $response['data'][$key] ?? $response[$key] ?? null;

            if (is_numeric($amount)) {
                return (float) $amount;
            }
        }

        return null;
    }

    /**
     * Read the currency PayGlocal reports having charged in, when it reports one.
     */
    public function getCapturedCurrency(?array $response): ?string
    {
        foreach (['txnCurrency', 'currency'] as $key) {
            $currency = $response['data'][$key] ?? $response[$key] ?? null;

            if (! empty($currency)) {
                return strtoupper((string) $currency);
            }
        }

        return null;
    }

    /**
     * Initiate a hosted checkout payment.
     */
    public function initiatePayment($cart, string $merchantTxnId): ?array
    {
        $currency = $this->getCurrency($cart);

        $response = $this->request('POST', '/gl/v1/payments/initiate/paycollect', [
            'merchantTxnId' => $merchantTxnId,

            'paymentData' => [
                'totalAmount' => $this->formatAmount($cart->base_grand_total, $currency),
                'txnCurrency' => $currency,
                'billingData' => $this->prepareBillingData($cart),
            ],

            'merchantCallbackURL' => route('payglocal.callback'),
        ]);

        if (empty($response['data']['redirectUrl'])) {
            return null;
        }

        return array_merge($response['data'], [
            'gid' => $response['gid'] ?? null,
        ]);
    }

    /**
     * Fetch the authoritative status of a transaction from PayGlocal.
     */
    public function getTransactionStatus(?string $statusUrl): ?array
    {
        if (empty($statusUrl)) {
            return null;
        }

        try {
            $response = Http::acceptJson()->get($statusUrl);

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            report($e);

            return null;
        }
    }

    /**
     * Send a signed and encrypted request to PayGlocal.
     */
    protected function request(string $method, string $endpoint, array $payload): ?array
    {
        try {
            $jwe = $this->crypto->encrypt($payload);

            $jws = $this->crypto->sign($jwe);

            $response = Http::withHeaders([
                'x-gl-token-external' => $jws,
                'Content-Type' => 'text/plain',
            ])
                ->withBody($jwe, 'text/plain')
                ->send($method, $this->getBaseUrl().$endpoint);

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            report($e);

            return null;
        }
    }

    /**
     * Format an amount the way PayGlocal expects it.
     */
    protected function formatAmount(float $amount, ?string $currencyCode = null): string
    {
        return number_format($amount, $this->getCurrencyDecimal($currencyCode), '.', '');
    }

    /**
     * Get the number of decimals a currency is charged with.
     *
     * Resolved from the currency itself when one is named, rather than from the session. The
     * webhook runs with no session and has to reach the same answer the browser did, so an
     * amount is only ever compared with the decimals of the currency it was captured in.
     */
    public function getCurrencyDecimal(?string $currencyCode = null): int
    {
        if ($currencyCode) {
            $currency = core()->getAllCurrencies()->firstWhere('code', strtoupper($currencyCode));

            if ($currency) {
                return $currency->decimal ?? 2;
            }
        }

        return core()->getBaseCurrency()?->decimal ?? 2;
    }

    /**
     * Get the currency the payment is charged in.
     */
    public function getCurrency($cart): string
    {
        return strtoupper($cart->base_currency_code ?: core()->getBaseCurrencyCode());
    }

    /**
     * Prepare the billing details sent along with the payment.
     */
    protected function prepareBillingData($cart): array
    {
        $address = $cart->billing_address;

        if (! $address) {
            return [];
        }

        return array_filter([
            'firstName' => $address->first_name,
            'lastName' => $address->last_name,
            'emailId' => $address->email ?? $cart->customer_email,
            'phoneNumber' => $address->phone,
            'addressStreet1' => $address->address,
            'addressCity' => $address->city,
            'addressState' => $address->state,
            'addressPostalCode' => $address->postcode,
            'addressCountry' => $address->country,
        ]);
    }
}
