<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;

class MoneyTransfer extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'moneytransfer';

    public function getRedirectUrl()
    {

    }

    /**
     * Returns payment method additional information
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        if (empty($this->getConfigData('mailing_address'))) {
            return [];
        }

        return [
            'title' => trans('admin::app.configuration.mailing-address'),
            'value' => $this->getConfigData('mailing_address'),
        ];
    }

    /**
     * Returns payment method image
     *
     * @return array
     */
    public function getImage()
    {
        $imageUrl = $this->getConfigData('image');

        return $imageUrl ? Storage::url($imageUrl) : bagisto_asset('images/money-transfer.png');
    }
}
