<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;

class CashOnDelivery extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'cashondelivery';

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
    }

    /**
     * Is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->cart->haveStockableItems() && $this->getConfigData('active');
    }

    /**
     * Get payment method image.
     *
     * @return array
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/cash-on-delivery.png', 'shop');
    }
}
