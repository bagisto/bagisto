<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\Checkout\Contracts\Cart as CartContract;

class Cart extends Model implements CartContract
{
    protected $table = 'cart';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['coupon_code'];

    protected $with = ['items', 'items.children'];

    /**
     * To get relevant associated items with the cart instance
     */
    public function items() {
        return $this->hasMany(CartItemProxy::modelClass())->whereNull('parent_id');
    }

    /**
     * To get all the associated items with the cart instance even the parent and child items of configurable products
     */
    public function all_items() {
        return $this->hasMany(CartItemProxy::modelClass());
    }

    /**
     * Get the addresses for the cart.
     */
    public function addresses()
    {
        return $this->hasMany(CartAddressProxy::modelClass());
    }

    /**
     * Get the biling address for the cart.
     */
    public function billing_address()
    {
        return $this->addresses()->where('address_type', 'billing');
    }

    /**
     * Get billing address for the cart.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }

    /**
     * Get the shipping address for the cart.
     */
    public function shipping_address()
    {
        return $this->addresses()->where('address_type', 'shipping');
    }

    /**
     * Get shipping address for the cart.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }

    /**
     * Get the shipping rates for the cart.
     */
    public function shipping_rates()
    {
        return $this->hasManyThrough(CartShippingRateProxy::modelClass(), CartAddressProxy::modelClass(), 'cart_id', 'cart_address_id');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function selected_shipping_rate()
    {
        return $this->shipping_rates()->where('method', $this->shipping_method);
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getSelectedShippingRateAttribute()
    {
        return $this->selected_shipping_rate()->where('method', $this->shipping_method)->first();
    }

    /**
     * Get the payment associated with the cart.
     */
    public function payment()
    {
        return $this->hasOne(CartPaymentProxy::modelClass());
    }

    /**
     * Checks if cart have stockable items
     *
     * @return boolean
     */
    public function haveStockableItems()
    {
        foreach ($this->items as $item) {
            if ($item->product->isStockable())
                return true;
        }

        return false;
    }

    /**
     * Checks if cart has downloadable items
     *
     * @return boolean
     */
    public function haveDownloadableItems()
    {
        foreach ($this->items as $item) {
            if (stristr($item->type,'downloadable') !== false) {
                return true;
            }
        }

        return false;
    }
}