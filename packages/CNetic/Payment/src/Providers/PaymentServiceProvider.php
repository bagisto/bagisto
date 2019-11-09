<?php

namespace CNetic\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Payment\Providers\PaymentServiceProvider as BasePaymentServiceProvider;

/**
* Payment service provider
*
* @author    Dariusz Męciński
* @copyright 2019 CNetic Software
*/
class PaymentServiceProvider extends BasePaymentServiceProvider
{


  /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {        
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php',
            'paymentmethods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }
}
