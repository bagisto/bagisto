<?php

namespace Webkul\Cart\Models;

use Illuminate\Database\Eloquent\Model;

use Webkul\Product\Models\Product;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = ['customer_id', 'session_id', 'channel_id', 'coupon_code', 'is_gift'];

    protected $hidden = ['coupon_code'];

    public function with_products() {

        return $this->belongsToMany(Product::class, 'cart_products')->withPivot('id', 'product_id','quantity', 'cart_id');
    }
}
