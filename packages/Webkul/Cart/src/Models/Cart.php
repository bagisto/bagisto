<?php

namespace Webkul\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;
use Webkul\Cart\Models\CartAddress;
use Webkul\Cart\Models\CartShipping;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = ['customer_id', 'session_id', 'channel_id', 'coupon_code', 'is_gift'];

    protected $hidden = ['coupon_code'];

    public function with_products() {

        return $this->belongsToMany(Product::class, 'cart_items')->withPivot('id', 'product_id','quantity', 'cart_id');
    }

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
