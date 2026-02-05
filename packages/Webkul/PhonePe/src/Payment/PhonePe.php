<?php

namespace Webkul\PhonePe\Payment;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Webkul\Payment\Payment\Payment;

class PhonePe extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'phonepe';

    /**
     * Get redirect URL for PayU payment.
     */
    public function getRedirectUrl(): string
    {
        return route('phonepe.redirect');
    }

    /**
     * Is available.
     */
    public function isAvailable(): bool
    {
        return parent::isAvailable() && $this->hasValidCredentials();
    }

    /**
     * Get payment method image.
     */
    public function getImage(): string
    {
        $url = $this->getConfigData('image');
        
        return $url ? Storage::url($url) : bagisto_asset('images/phonepe.png', 'shop');
    }

    /**
     * Get merchant Id from configuration.
     */
    public function getMerchantId(): string|null
    {
        return $this->getConfigData('merchant_id');
    }

    /**
     * Get merchant key from configuration.
     */
    public function getClientSecret(): string|null
    {
        return $this->getConfigData('client_secret');
    }

    /**
     * Get merchant key from configuration.
     */
    public function getClientId(): string|null
    {
        return $this->getConfigData('client_id');
    }

    /**
     * Check if sandbox mode is enabled.
     */
    public function isSandbox(): bool
    {
        return (bool) $this->getConfigData('sandbox');
    }

    /**
     * Get payment gateway URL based on environment.
     */
    public function getPaymentUrl(): string
    {
        return $this->isSandbox()
            ? 'https://api-preprod.phonepe.com/apis/pg-sandbox'
            : 'https://api.phonepe.com/apis/pg';
    }

    /**
     * Generate unique merchant order ID.
     */
    public function generateMerchantOrderId(int $cartId): string
    {
        return 'PHONE_PE_' . $cartId . '_' . Str::upper(Str::random(8));
    }

    /**
     * Generate access token for PhonePe API.
     */
    public function getAccessToken(): string
    {
        return Cache::remember('phonepe_access_token', 3500, function () {
            $response = Http::asForm()->post(
                $this->getPaymentUrl() . '/v1/oauth/token', [
                    'client_id' => $this->getClientId(),
                    'client_secret' => $this->getClientSecret(),
                    'client_version' => 1,
                    'grant_type' => 'client_credentials',
                ]
            );

            return $response->json('access_token') ?? '';
        });
    }

    /**
     * Check if merchant credentials are valid.
     */
    public function initiatePayment($cart): string
    {
        /**
         * Prepare payment data payload for PhonePe API, including merchant order ID, amount, and callback URL.
         * The callback URL will be used by PhonePe to notify about payment status changes.
         */
        $payload = $this->getPaymentData($cart);

        $response = Http::withHeaders($this->getHeader())->post(
            $this->getPaymentUrl() . '/checkout/v2/pay',
            $payload
        );

        return $response->json('redirectUrl') ?? null;
    }

    /**
     * Check payment status for a given merchant order ID.
     */
    public function checkPaymentStatus(string $merchantOrderId): array
    {
        $response = Http::withHeaders($this->getHeader())->get(
            $this->getPaymentUrl() . '/checkout/v2/order/'.$merchantOrderId.'/status'
        );

        $data = $response->json() ?? [];
        $data['token'] = $this->getAccessToken() ?? null;

        $state = strtoupper($data['state'] ?? $data['status'] ?? '');

        return [
            'data' => $data,
            'raw' => $response->json(),
        ];
    }

    /**
     * Validate merchant credentials.
     */
    public function hasValidCredentials(): bool
    {
        return ! empty($this->getMerchantId()) && ! empty($this->getClientSecret() && ! empty($this->getClientId()));
    }

    /**
     * Generate cache key for a given merchant order ID.
     */
    public function cacheKey(string $merchantOrderId): string
    {
        return 'phonepe:order:' . $merchantOrderId;
    }

    /**
     * Generate payment data payload for PhonePe API.
     */
    private function getPaymentData($cart): array
    {
        $merchantOrderId = $this->generateMerchantOrderId($cart->id);

        Cache::put($this->cacheKey($merchantOrderId), ['cart_id' => $cart->id], now()->addMinutes(60));

        session()->put('phonepe.merchant_order_id', $merchantOrderId);

        return [
            'merchantOrderId' => $merchantOrderId,
            'amount' => (int) round($cart->base_grand_total * 100),
            'expireAfter' => 1200,

            'paymentFlow' => [
                'type' => 'PG_CHECKOUT',
                "message" => "Payment message used for collect requests",
                "merchantUrls" => [
                    "redirectUrl" => route('phonepe.callback', ['orderId' => $merchantOrderId]),
                ],
            ],

            'metaInfo' => [
                'udf1' => (string) $cart->id,
                'udf2' => $cart->customer_email ?? null,
            ],
        ];
    }

    /**
     * Get headers for PhonePe API requests.
     */
    private function getHeader(): array
    {
        return [
            'Authorization' => 'O-Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ];
    }
}