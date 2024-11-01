<?php

namespace Frosko\DSK\Payment;

use Illuminate\Support\Facades\Storage;
use Webkul\Payment\Payment\Payment;

class DSK extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'dsk';

    public function getRedirectUrl(): string
    {
        return route('dsk.payment.redirect');
    }

    public function getImage(): string
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/dsk.png', 'shop');
    }

    public function getConfigData($field): mixed
    {
        return core()->getConfigData('sales.payment_methods.dsk.'.$field);
    }
}
