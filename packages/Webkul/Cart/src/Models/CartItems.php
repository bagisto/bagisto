<?php

namespace Webkul\Cart\Models;

class CartItems
{
    protected $table = 'cart_items';

    protected $fillable = ['product_id','quantity','cart_id','tax_category_id','coupon_code'];
}
