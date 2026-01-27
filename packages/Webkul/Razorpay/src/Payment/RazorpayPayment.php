<?php

namespace Webkul\Razorpay\Payment;

use Illuminate\Support\Facades\Storage;
use Razorpay\Api\Api;
use Webkul\Checkout\Facades\Cart;
use Webkul\Payment\Payment\Payment;

class RazorpayPayment extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'razorpay';

    /**
     * Supported currencies.
     *
     * @var array
     */
    protected $supportedCurrencies = ['INR'];

    /**
     * Receipt prefix.
     */
    public const RECEIPT_PREFIX = 'receipt_';

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('razorpay.payment.redirect');
    }

    /**
     * Check if payment method is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return parent::isAvailable() && $this->hasValidCredentials();
    }

    /**
     * Get payment method title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfigData('title') ?? trans('razorpay::app.title');
    }

    /**
     * Get payment method description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getConfigData('description') ?? trans('razorpay::app.description');
    }

    /**
     * Returns payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/razorpay.png', 'shop');
    }

    /**
     * Check if sandbox mode is enabled.
     *
     * @return bool
     */
    public function isSandbox()
    {
        return (bool) $this->getConfigData('sandbox');
    }

    /**
     * Get Razorpay API Key (Client ID).
     *
     * @return string|null
     */
    public function getApiKey()
    {
        return $this->isSandbox()
            ? $this->getConfigData('test_client_id')
            : $this->getConfigData('client_id');
    }

    /**
     * Get Razorpay API Secret (Client Secret).
     *
     * @return string|null
     */
    public function getApiSecret()
    {
        return $this->isSandbox()
            ? $this->getConfigData('test_client_secret')
            : $this->getConfigData('client_secret');
    }

    /**
     * Get merchant name.
     *
     * @return string|null
     */
    public function getMerchantName()
    {
        return $this->getConfigData('merchant_name');
    }

    /**
     * Get merchant description.
     *
     * @return string|null
     */
    public function getMerchantDescription()
    {
        return $this->getConfigData('merchant_desc');
    }

    /**
     * Check if required credentials are configured.
     *
     * @return bool
     */
    public function hasValidCredentials()
    {
        return $this->getApiKey() && $this->getApiSecret();
    }

    /**
     * Get supported currencies.
     *
     * @return array
     */
    public function getSupportedCurrencies()
    {
        return $this->supportedCurrencies;
    }

    /**
     * Check if currency is supported.
     *
     * @param  string  $currency
     * @return bool
     */
    public function isCurrencySupported($currency)
    {
        return in_array(strtoupper($currency), $this->supportedCurrencies);
    }

    /**
     * Get Razorpay API instance.
     *
     * @return \Razorpay\Api\Api
     */
    public function getApi()
    {
        return new Api($this->getApiKey(), $this->getApiSecret());
    }

    /**
     * Create Razorpay order.
     *
     * @param  \Webkul\Checkout\Contracts\Cart|null  $cart
     * @return array
     *
     * @throws \Exception
     */
    public function createOrder($cart = null)
    {
        if (! $cart) {
            $cart = Cart::getCart();
        }

        $currency = strtoupper($cart->base_currency_code ?? core()->getBaseCurrencyCode());

        if (! $this->isCurrencySupported($currency)) {
            throw new \Exception(trans('razorpay::app.response.supported-currency-error', [
                'currency' => $currency,
                'supportedCurrencies' => implode(', ', $this->supportedCurrencies),
            ]));
        }

        $api = $this->getApi();

        return $api->order->create([
            'amount' => (int) ($cart->base_grand_total * 100),
            'currency' => $currency,
            'receipt' => self::RECEIPT_PREFIX.$cart->id,
            'payment_capture' => 1,
            'notes' => [
                'cart_id' => $cart->id,
            ],
        ]);
    }

    /**
     * Prepare payment data for checkout.
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @param  array  $razorpayOrder
     * @return array
     */
    public function preparePaymentData($cart, $razorpayOrder)
    {
        return [
            'key' => $this->getApiKey(),
            'amount' => (int) ($cart->base_grand_total * 100),
            'currency' => strtoupper($cart->base_currency_code ?? core()->getBaseCurrencyCode()),
            'name' => $this->getMerchantName(),
            'description' => $this->getMerchantDescription(),
            'image' => $this->getImage(),
            'order_id' => $razorpayOrder['id'],
            'theme_color' => '#0041FF',
            'prefill' => [
                'name' => $cart->billing_address->name,
                'email' => $cart->billing_address->email,
                'contact' => $cart->billing_address->phone,
            ],
        ];
    }

    /**
     * Verify Razorpay payment signature.
     *
     * @param  string  $orderId
     * @param  string  $paymentId
     * @param  string  $signature
     * @return bool
     */
    public function verifySignature($orderId, $paymentId, $signature)
    {
        if (! $orderId || ! $paymentId || ! $signature) {
            return false;
        }

        $expectedSignature = $orderId.'|'.$paymentId;

        $generatedSignature = hash_hmac('sha256', $expectedSignature, $this->getApiSecret());

        return hash_equals($generatedSignature, $signature);
    }
}
