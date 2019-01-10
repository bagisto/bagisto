<?php

namespace Webkul\Payment\Payment;

/**
 * Cash On Delivery payment method class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CashOnDelivery extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'cashondelivery';

    public function getRedirectUrl()
    {
        
    }
}