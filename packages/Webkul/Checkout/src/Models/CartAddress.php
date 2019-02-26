<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Checkout\Contracts\CartAddress as CartAddressContract;

class CartAddress extends Model implements CartAddressContract
{
    protected $table = 'cart_address';

    protected $fillable = ['first_name', 'last_name', 'email', 'address1', 'address2', 'city', 'state', 'postcode',  'country', 'phone', 'address_type', 'cart_id'];

    /**
     * Get the shipping rates for the cart address.
     */
    public function shipping_rates()
    {
        return $this->hasMany(CartShippingRateProxy::modelClass());
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}