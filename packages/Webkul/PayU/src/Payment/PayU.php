<?php

namespace Webkul\PayU\Payment;

use Illuminate\Support\Facades\Storage;
use Webkul\Checkout\Facades\Cart;
use Webkul\Payment\Payment\Payment;

class PayU extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'payu';

    /**
     * Get redirect URL for PayU payment.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('payu.redirect');
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
        return $this->getConfigData('title') ?? trans('payu::app.title');
    }

    /**
     * Get payment method description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getConfigData('description') ?? trans('payu::app.description');
    }

    /**
     * Get payment method image/logo.
     *
     * @return string|null
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/payu.png', 'shop');
    }

    /**
     * Get merchant key from configuration.
     *
     * @return string|null
     */
    public function getMerchantKey()
    {
        return $this->getConfigData('merchant_key');
    }

    /**
     * Get merchant salt from configuration.
     *
     * @return string|null
     */
    public function getMerchantSalt()
    {
        return $this->getConfigData('merchant_salt');
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
     * Get payment gateway URL based on environment.
     *
     * @return string
     */
    public function getPaymentUrl()
    {
        return $this->isSandbox()
            ? 'https://test.payu.in/_payment'
            : 'https://secure.payu.in/_payment';
    }

    /**
     * Generate payment data for PayU gateway.
     *
     * @param  \Webkul\Checkout\Contracts\Cart|null  $cart
     * @return array
     */
    public function getPaymentData($cart = null)
    {
        if (! $cart) {
            $cart = Cart::getCart();
        }

        $txnid = uniqid('PAYU_');
        $amount = round($cart->base_grand_total, 2);
        $productInfo = 'Order #'.$cart->id;
        $firstname = $cart->customer_first_name;
        $email = $cart->customer_email;

        return [
            'key' => $this->getMerchantKey(),
            'txnid' => $txnid,
            'amount' => $amount,
            'productinfo' => $productInfo,
            'firstname' => $firstname,
            'email' => $email,
            'phone' => $cart->billing_address->phone ?? '',
            'surl' => route('payu.success'),
            'furl' => route('payu.failure'),
            'curl' => route('payu.cancel'),
            'hash' => $this->generateHash($txnid, $amount, $productInfo, $firstname, $email, $cart->id),
            'udf1' => $cart->id,
        ];
    }

    /**
     * Generate hash for PayU payment request.
     *
     * @param  string  $txnid
     * @param  float  $amount
     * @param  string  $productInfo
     * @param  string  $firstname
     * @param  string  $email
     * @param  string  $udf1
     * @return string
     */
    public function generateHash($txnid, $amount, $productInfo, $firstname, $email, $udf1 = '')
    {
        $hashString = $this->getMerchantKey().'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|'.$udf1.'||||||||||'.$this->getMerchantSalt();

        return strtolower(hash('sha512', $hashString));
    }

    /**
     * Verify hash from PayU response.
     *
     * @return bool
     */
    public function verifyHash(array $response)
    {
        $status = $response['status'] ?? '';
        $firstname = $response['firstname'] ?? '';
        $amount = $response['amount'] ?? '';
        $txnid = $response['txnid'] ?? '';
        $key = $response['key'] ?? '';
        $productInfo = $response['productinfo'] ?? '';
        $email = $response['email'] ?? '';
        $udf1 = $response['udf1'] ?? '';
        $receivedHash = $response['hash'] ?? '';

        $hashString = $this->getMerchantSalt().'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productInfo.'|'.$amount.'|'.$txnid.'|'.$key;

        $calculatedHash = strtolower(hash('sha512', $hashString));

        return $calculatedHash === $receivedHash;
    }

    /**
     * Validate merchant credentials.
     *
     * @return bool
     */
    public function hasValidCredentials()
    {
        return ! empty($this->getMerchantKey()) && ! empty($this->getMerchantSalt());
    }
}
