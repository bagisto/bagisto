<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;

class MoneyTransfer extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'moneytransfer';

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl() {}

    /**
     * Returns payment method additional information.
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        if (empty($this->getConfigData('mailing_address'))) {
            return [];
        }

        return [
            'title' => trans('admin::app.configuration.index.sales.payment-methods.mailing-address'),
            'value' => $this->getConfigData('mailing_address'),
        ];
    }

    /**
     * Returns payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/money-transfer.png', 'shop');
    }
}
