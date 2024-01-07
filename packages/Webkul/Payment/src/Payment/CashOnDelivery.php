<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;

class CashOnDelivery extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'cashondelivery';

    public function getRedirectUrl()
    {

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
