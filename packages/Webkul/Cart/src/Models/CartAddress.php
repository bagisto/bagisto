<?php

namespace Webkul\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Cart\Models\CartShippingRate;

class CartAddress extends Model
{
    protected $table = 'cart_address';

    protected $fillable = ['first_name', 'last_name', 'email', 'address1', 'address2', 'city', 'state', 'postcode',  'country',  'email', 'phone', 'address_type', 'cart_id'];

    /**
     * Get the shipping rates for the cart address.
     */
    public function shipping_rates()
    {
        return $this->hasMany(CartShippingRate::class);
    }
}