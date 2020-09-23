<?php

namespace Webkul\Paypal\Payment;

class SmartButton extends Paypal
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'paypal_smart_button';

    /**
     * Return paypal redirect url
     *
     * @return string
     */
    public function getRedirectUrl()
    {
    }
}