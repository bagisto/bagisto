<?php

namespace Webkul\Paytm\Payment;

use Illuminate\Support\Facades\Storage;
use Webkul\Checkout\Contracts\Cart as CartContract;
use Webkul\Payment\Payment\Payment;

class Paytm extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'paytm';

    /**
     * Get redirect URL for payment processing.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('paytm.redirect');
    }

    /**
     * Get payment method title.
     *
     * @return string
     */
    public function getTitle()
    {
        return trans($this->getConfigData('title'));
    }

    /**
     * Get payment method description.
     *
     * @return string
     */
    public function getDescription()
    {
        return trans($this->getConfigData('description'));
    }

    /**
     * Get Paytm gateway URL based on environment.
     *
     * @return string
     */
    public function getPaytmUrl()
    {
        return $this->getConfigData('sandbox')
            ? 'https://securegw-stage.paytm.in/order/process'
            : 'https://securegw.paytm.in/order/process';
    }

    /**
     * Returns payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/paytm.png', 'shop');
    }

    /**
     * Build Paytm form fields for redirect.
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return array
     */
    public function getFormFields(CartContract $cart)
    {
        $billingAddress = $cart->billing_address;
        $orderId = $this->generateOrderId($cart->id);
        $this->ensureChecksumLoaded();

        $fields = [
            'MID' => $this->getConfigData('merchant_id'),
            'ORDER_ID' => $orderId,
            'CUST_ID' => (string) ($cart->customer_id ?: ('guest-'.$cart->id)),
            'TXN_AMOUNT' => number_format((float) $cart->grand_total, 2, '.', ''),
            'CHANNEL_ID' => 'WEB',
            'WEBSITE' => $this->getWebsite(),
            'INDUSTRY_TYPE_ID' => 'Retail109',
            'CALLBACK_URL' => route('paytm.callback'),
            'EMAIL' => $billingAddress?->email ?? $cart->customer_email,
            'MOBILE_NO' => $billingAddress?->phone ?? '',
        ];

        $fields['CHECKSUMHASH'] = \PaytmChecksum::generateSignature(
            $fields,
            (string) $this->getConfigData('merchant_key')
        );

        return $fields;
    }

    /**
     * Verify Paytm checksum from response.
     *
     * @param  array  $payload
     * @return bool
     */
    public function verifyChecksum(array $payload)
    {
        $received = $payload['CHECKSUMHASH'] ?? '';

        if ($received === '') {
            return false;
        }

        $this->ensureChecksumLoaded();

        unset($payload['CHECKSUMHASH']);

        return \PaytmChecksum::verifySignature(
            $payload,
            (string) $this->getConfigData('merchant_key'),
            $received
        );
    }

    /**
     * Get website code based on environment.
     *
     * @return string
     */
    protected function getWebsite()
    {
        return $this->getConfigData('sandbox')
            ? 'WEBSTAGING'
            : 'DEFAULT';
    }

    /**
     * Generate unique order id for Paytm.
     *
     * @param  int  $cartId
     * @return string
     */
    protected function generateOrderId(int $cartId)
    {
        return 'PAYTM_'.time().'_'.$cartId;
    }

    /**
     * Ensure Paytm checksum class is available.
     *
     * @return void
     */
    protected function ensureChecksumLoaded()
    {
        if (class_exists('PaytmChecksum')) {
            return;
        }

        $checksumPath = base_path('vendor/paytm/paytmchecksum/PaytmChecksum.php');

        if (file_exists($checksumPath)) {
            require_once $checksumPath;
        }
    }
}
