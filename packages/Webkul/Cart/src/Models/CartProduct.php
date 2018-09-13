<?php

namespace Webkul\Cart\Models;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    protected $table = 'cart_products';

    protected $fillable = ['product_id','quantity','cart_id','tax_category_id','coupon_code'];
}
