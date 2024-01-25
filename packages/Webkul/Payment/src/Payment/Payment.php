<?php

namespace Webkul\Payment\Payment;

use Webkul\Checkout\Facades\Cart;

abstract class Payment
{
    /**
     * Cart.
     *
     * @var \Webkul\Checkout\Contracts\Cart
     */
    protected $cart;

    /**
     * Checks if payment method is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->getConfigData('active');
    }

    /**
     * Get payment method code.
     *
     * @return string
     */
    public function getCode()
    {
        if (empty($this->code)) {
            // throw exception
        }

        return $this->code;
    }

    /**
     * Get payment method title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfigData('title');
    }

    /**
     * Get payment method description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getConfigData('description');
    }

    /**
     * Get payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getConfigData('image');
    }

    /**
     * Retrieve information from payment configuration.
     *
     * @param  string  $field
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.payment_methods.'.$this->getCode().'.'.$field);
    }

    /**
     * Abstract method to get the redirect URL.
     *
     * @return string The redirect URL.
     */
    abstract public function getRedirectUrl();

    /**
     * Set cart.
     *
     * @var void
     */
    public function setCart()
    {
        if (! $this->cart) {
            $this->cart = Cart::getCart();
        }
    }

    /**
     * Get cart.
     *
     * @return \Webkul\Checkout\Contracts\Cart
     */
    public function getCart()
    {
        if (! $this->cart) {
            $this->setCart();
        }

        return $this->cart;
    }

    /**
     * Return paypal redirect url.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public function getCartItems()
    {
        if (! $this->cart) {
            $this->setCart();
        }

        return $this->cart->items;
    }

    /**
     * Get payment method sort order.
     *
     * @return string
     */
    public function getSortOrder()
    {
        return $this->getConfigData('sort');
    }

    /**
     * Get payment method additional information.
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        if (empty($this->getConfigData('instructions'))) {
            return [];
        }

        return [
            'title' => trans('admin::app.configuration.instructions'),
            'value' => $this->getConfigData('instructions'),
        ];
    }
}
