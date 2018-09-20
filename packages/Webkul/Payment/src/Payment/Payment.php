<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Config;

abstract class Payment
{
    /**
     * Checks if payment method is available
     *
     * @return array
     */
    public function isAvailable()
    {
        return $this->getConfigData('active');
    }

    /**
     * Returns payment method code
     *
     * @return array
     */
    public function getCode()
    {
        if (empty($this->code)) {
            // throw exception
        }

        return $this->_code;
    }

    /**
     * Returns payment method title
     *
     * @return array
     */
    public function getTitle()
    {
        return $this->getConfigData('title');
    }

    /**
     * Returns payment method decription
     *
     * @return array
     */
    public function getDecription()
    {
        return $this->getConfigData('decription');
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param int|string|null $channelId
     *
     * @return mixed
     */
    public function getConfigData($field, $channelId = null)
    {
        if (null === $channelId) {
            $channelId = core()->getCurrentChannel()->getId();
        }

        $paymentConfig = Config::get('paymentmethods' . $this->getCode());

        return $paymentConfig[$field];
    }
}