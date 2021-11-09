<?php

namespace Webkul\Shipping\Carriers;

abstract class AbstractShipping
{
    /**
     * Shipping method carrier code
     *
     * @var string
     */
    protected $code;

    /**
     * Shipping method code
     *
     * @var string
     */
    protected $method;

    abstract public function calculate();

    /**
     * Checks if shipping method is available
     *
     * @return array
     */
    public function isAvailable()
    {
        return $this->getConfigData('active');
    }

    /**
     * Returns shipping method code
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

    public function getMethod()
    {
        if (empty($this->method)) {
            // throw exception
        }

        return $this->method;
    }

    /**
     * Returns shipping method title
     *
     * @return array
     */
    public function getTitle()
    {
        return $this->getConfigData('title');
    }

    /**
     * Returns shipping method description
     *
     * @return array
     */
    public function getDescription()
    {
        return $this->getConfigData('description');
    }

    /**
     * Retrieve information from shipping configuration
     *
     * @param string $field
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.carriers.' . $this->getCode() . '.' . $field);
    }
}
