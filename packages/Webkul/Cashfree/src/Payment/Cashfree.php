<?php

namespace Webkul\Cashfree\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Payment\Payment\Payment;

class Cashfree extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'cashfree';

    /**
     * Currencies Cashfree settles in.
     *
     * @var string[]
     */
    protected $supportedCurrencies = ['INR'];

    /**
     * Get the currencies Cashfree settles in.
     *
     * @return  string[]
     */
    public function getSupportedCurrencies(): array
    {
        return $this->supportedCurrencies;
    }

    /**
     * Check whether Cashfree settles in the given currency.
     *
     * @param  string  $currency
     */
    public function isCurrencySupported(string $currency): bool
    {
        return in_array(strtoupper($currency), $this->supportedCurrencies);
    }

    /**
     * Get redirect URL for Cashfree payment.
     */
    public function getRedirectUrl(): string
    {
        return route('cashfree.redirect');
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

        return $url ? Storage::url($url) : bagisto_asset('images/cashfree.png', 'shop');
    }

    /**
     * Get App ID from configuration.
     */
    public function getAppId(): ?string
    {
        return $this->getConfigData('app_id');
    }

    /**
     * Get Secret Key from configuration.
     */
    public function getSecretKey(): ?string
    {
        return $this->getConfigData('secret_key');
    }

    /**
     * Check if sandbox mode is enabled.
     */
    public function isSandbox(): bool
    {
        return (bool) $this->getConfigData('sandbox');
    }

    /**
     * Get API base URL based on environment.
     */
    public function getApiUrl(): string
    {
        return $this->isSandbox()
            ? 'https://sandbox.cashfree.com/pg'
            : 'https://api.cashfree.com/pg';
    }

    /**
     * Validate credentials.
     */
    public function hasValidCredentials(): bool
    {
        return ! empty($this->getAppId()) && ! empty($this->getSecretKey());
    }

    /**
     * Generate unique Cashfree order ID.
     *
     * @param  int  $cartId
     */
    public function generateOrderId(int $cartId): string
    {
        return 'CF_'.$cartId.'_'.Str::upper(Str::random(8));
    }

    /**
     * Create order session on Cashfree.
     *
     * @param  object  $cart
     * @return  array|null
     */
    public function createOrder($cart): ?array
    {
        try {
            $orderId = $this->generateOrderId($cart->id);

            $payload = [
                'order_id'       => $orderId,
                'order_amount'   => (float) number_format($cart->base_grand_total, 2, '.', ''),
                'order_currency' => 'INR',
                'customer_details' => [
                    'customer_id'    => 'cust_'.($cart->customer_id ?? 'guest_'.$cart->id),
                    'customer_name'  => trim(($cart->billing_address?->first_name ?? 'Customer').' '.($cart->billing_address?->last_name ?? '')),
                    'customer_email' => $cart->customer_email ?? $cart->billing_address?->email ?? 'guest@example.com',
                    'customer_phone' => preg_replace('/[^0-9]/', '', $cart->billing_address?->phone ?? '9999999999') ?: '9999999999',
                ],
                'order_meta' => [
                    'return_url' => route('cashfree.callback').'?order_id={order_id}',
                    'notify_url' => route('cashfree.webhook'),
                ],
                'order_note' => 'Order for Cart #'.$cart->id,
            ];

            $response = Http::withHeaders($this->getHeaders())->post(
                $this->getApiUrl().'/orders',
                $payload
            );

            if ($response->successful()) {
                return $response->json();
            }

            logger()->error('Cashfree Order Creation Failed: '.$response->body());

            return null;
        } catch (\Exception $e) {
            logger()->error('Cashfree Order Creation Exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Fetch order details from Cashfree.
     *
     * @param  string  $orderId
     * @return  array|null
     */
    public function getOrder(string $orderId): ?array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get(
                $this->getApiUrl().'/orders/'.$orderId
            );

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            logger()->error('Cashfree Fetch Order Failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Fetch payment attempts for an order from Cashfree.
     *
     * @param  string  $orderId
     * @return  array
     */
    public function getOrderPayments(string $orderId): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get(
                $this->getApiUrl().'/orders/'.$orderId.'/payments'
            );

            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            logger()->error('Cashfree Fetch Order Payments Failed: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Verify webhook signature.
     *
     * @param  string  $rawPayload
     * @param  string  $timestamp
     * @param  string  $signature
     */
    public function verifyWebhookSignature(string $rawPayload, string $timestamp, string $signature): bool
    {
        $expected = base64_encode(hash_hmac('sha256', $timestamp.$rawPayload, $this->getSecretKey(), true));

        return hash_equals($expected, $signature);
    }

    /**
     * Get headers for Cashfree PG API.
     *
     * @return  array
     */
    private function getHeaders(): array
    {
        return [
            'x-client-id'     => $this->getAppId(),
            'x-client-secret' => $this->getSecretKey(),
            'x-api-version'   => '2023-08-01',
            'Content-Type'    => 'application/json',
            'Accept'          => 'application/json',
        ];
    }
}
