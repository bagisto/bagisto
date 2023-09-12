<?php

namespace Webkul\Payment\Payment;

class CashOnDelivery extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'cash_on_delivery';

    public function getRedirectUrl()
    {
        
    }
}