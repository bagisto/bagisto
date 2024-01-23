<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;
use Webkul\Checkout\Facades\Cart;

class CashOnDelivery extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'cashondelivery';

    /**
     * Return cashondelivery redirect url
     *
     * @return string
     */
    public function getRedirectUrl()
    {

    }

    public function isAvailable()
    {
        return $this->cart->haveStockableItems() && $this->getConfigData('active');
    }

    /**
     * Returns payment method image
     *
     * @return array
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/cash-on-delivery.png', 'shop');
    }
}
