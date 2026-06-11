<?php

namespace Webkul\PagoMovil\Payment;

use Webkul\Payment\Payment\Payment;

class PagoMovil extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'pagomovil';

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('pagomovil.redirect');
    }

    /**
     * Get payment method configuration data.
     *
     * @param  string  $field
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.payment_methods.pagomovil.' . $field);
    }
}
