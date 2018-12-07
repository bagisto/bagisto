<?php

namespace Webkul\Admin;

use Illuminate\Support\Facades\Config;

/**
 * Facade for Configuartion.
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class Configuration {

    /**
     * ShippingMethods
     *
     * @var array
     */
    protected $shippingMethods = [];

    /**
     * Collects shipping methods
     *
     * @return array
     */
    public function getShippingMethod()
    {
        foreach(Config::get('carriers') as $shippingMethod) {
            $object = new $shippingMethod['class'];
            $shippingMethods[] = $object;
        }

        return $shippingMethods;
    }
}