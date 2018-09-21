<?php

namespace Webkul\Cart\Models;

use Illuminate\Database\Eloquent\Model;


class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = ['product_id','quantity','cart_id','tax_category_id','coupon_code'];

    public function product() {
        return $this->hasOne('Webkul\Product\Models\Product', 'id', 'product_id');
    }
}
