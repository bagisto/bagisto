<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;


class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $casts = [
        'additional' => 'array',
    ];

    protected $fillable = ['product_id', 'quantity', 'cart_id', 'sku', 'type', 'name', 'parent_id','tax_category_id', 'coupon_code', 'weight', 'total_weight', 'base_total_weight', 'price', 'total', 'base_total', 'total_with_discount', 'base_total_with_discount', 'base_price', 'custom_price',  'discount_percent', 'discount_amount', 'base_discount_amount', 'no_discount', 'free_shipping', 'additional'];

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * Get the child item.
     */
    public function child()
    {
        return $this->belongsTo(self::class, 'id', 'parent_id');
    }
}
