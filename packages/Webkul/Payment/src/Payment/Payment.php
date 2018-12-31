<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Config;
use Webkul\Checkout\Facades\Cart;

abstract class Payment
{
    /**
     * Cart object
     *
     * @var Cart
     */
    protected $cart;
    
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
     * Returns payment method description
     *
     * @return array
     */
    public function getDescription()
    {
        return $this->getConfigData('description');
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param int|string|null $channelId
     *
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('paymentmethods.' . $this->getCode() . '.' . $field);
    }

    abstract public function getRedirectUrl();

    /**
     * Assign cart
     *
     * @var void
     */
    public function setCart()
    {
        if(!$this->cart)
            $this->cart = Cart::getCart();
    }

    /**
     * Returns cart insrance
     *
     * @var mixed
     */
    public function getCart()
    {
        if(!$this->cart)
            $this->setCart();

        return $this->cart;
    }

    /**
     * Return paypal redirect url
     *
     * @var Collection
     */
    public function getCartItems()
    {
        if(!$this->cart)
            $this->setCart();

        return $this->cart->items;
    }
}