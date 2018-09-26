<?php

namespace Webkul\Cart\Models;

use Illuminate\Database\Eloquent\Model;


class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = ['product_id', 'quantity', 'cart_id', 'sku', 'type', 'name', 'parent_id','tax_category_id', 'coupon_code', 'weight', 'item_total_weight', 'base_item_total_weight', 'price', 'item_total', 'item_total_with_discount', 'base_item_total_with_discount', 'base_price', 'custom_price',  'discount_percent', 'discount_amount', 'base_discount_amount', 'no_discount', 'free_shipping', 'additional'];

    public function product() {
        return $this->hasOne('Webkul\Product\Models\Product', 'id', 'product_id');
    }
}
