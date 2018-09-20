<?php

namespace Webkul\Shipping\Carriers;

use Illuminate\Support\Facades\Config;

/**
 * Abstract class Shipping
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class AbstractShipping
{

    abstract public function calculate();

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

        return $this->code;
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
            $channelId = core()->getCurrentChannel()->id;
        }

        $shippingConfig = Config::get('carriers' . $this->getCode());

        return $shippingConfig[$field];
    }

}
?>