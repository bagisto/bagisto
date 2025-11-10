<?php

namespace Webkul\PayU\Payment;

use Webkul\Payment\Payment\Payment;

class PayU extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'payu';

    /**
     * Get redirect URL for PayU payment
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('payu.redirect');
    }

    /**
     * Check if payment method is available
     *
     * @return bool
     */
    public function isAvailable()
    {
        $merchantKey = core()->getConfigData('sales.payment_methods.payu.merchant_key');
        $merchantSalt = core()->getConfigData('sales.payment_methods.payu.merchant_salt');

        return parent::isAvailable() && !empty($merchantKey) && !empty($merchantSalt);
    }

    /**
     * Get payment method additional information
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        return [
            'title'       => $this->getTitle(),
            'description' => $this->getDescription(),
            'image'       => $this->getImage(),
        ];
    }

    /**
     * Get payment method title
     *
     * @return string
     */
    public function getTitle()
    {
        return core()->getConfigData('sales.payment_methods.payu.title') 
            ?? trans('payu::app.title');
    }

    /**
     * Get payment method description
     *
     * @return string
     */
    public function getDescription()
    {
        return core()->getConfigData('sales.payment_methods.payu.description') 
            ?? trans('payu::app.description');
    }

    /**
     * Get payment method image/logo
     *
     * @return string|null
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/stripe.png', 'shop');
    }
}