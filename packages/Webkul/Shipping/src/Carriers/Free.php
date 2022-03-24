<?php

namespace Webkul\Shipping\Carriers;

use Webkul\Checkout\Models\CartShippingRate;

class Free extends AbstractShipping
{
    /**
     * Shipping method carrier code.
     *
     * @var string
     */
    protected $code = 'free';

    /**
     * Shipping method code.
     *
     * @var string
     */
    protected $method = 'free_free';

    /**
     * Calculate rate for free shipping.
     *
     * @return CartShippingRate|false
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        return $this->getRate();
    }

    /**
     * Get rate.
     *
     * @return \Webkul\Checkout\Models\CartShippingRate
     */
    public function getRate(): \Webkul\Checkout\Models\CartShippingRate
    {
        $cartShippingRate = new CartShippingRate;

        $cartShippingRate->carrier = $this->getCode();
        $cartShippingRate->carrier_title = $this->getConfigData('title');
        $cartShippingRate->method = $this->getMethod();
        $cartShippingRate->method_title = $this->getConfigData('title');
        $cartShippingRate->method_description = $this->getConfigData('description');
        $cartShippingRate->price = 0;
        $cartShippingRate->base_price = 0;

        return $cartShippingRate;
    }
}
