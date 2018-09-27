<?php

namespace Webkul\Cart\Models;

use Illuminate\Database\Eloquent\Model;


class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = ['product_id','quantity','cart_id','tax_category_id','coupon_code', 'weight', 'price', 'base_price', 'discount_percent', 'discount_amount', 'base_discount_amount', 'no_discount', 'custom_price', 'additional'];

    public function product() {
        return $this->hasOne('Webkul\Product\Models\Product', 'id', 'product_id');
    }

    /**
     * Get the phone record associated with the user.
     */
    public function parent()
    {
        return $this->hasOne(self::class, 'parent_id');
    }

    /**
     * Get the phone record associated with the user.
     */
    public function child()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
