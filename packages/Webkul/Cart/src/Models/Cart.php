<?php

namespace Webkul\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;
use Webkul\Cart\Models\CartAddress;
use Webkul\Cart\Models\CartShipping;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = ['customer_id', 'session_id', 'channel_id', 'coupon_code', 'is_gift', 'global_currency_code', 'base_currency_code', 'store_currency_code', 'quote_currency_code', 'grand_total', 'base_grand_total', 'sub_total', 'base_sub_total', 'sub_total_with_discount', 'base_sub_total_with_discount', 'checkout_method', 'is_guest', 'customer_full_name', 'conversion_time'];

    protected $hidden = ['coupon_code'];

    public function items() {
        return $this->hasMany('Webkul\Cart\Models\CartItem');
    }

    /**
     * Get the addresses for the cart.
     */
    public function addresses()
    {
        return $this->hasMany(CartAddress::class);
    }

    /**
     * Get the shipping for the cart.
     */
    public function shipping()
    {
        return $this->hasMany(CartShipping::class);
    }
}
