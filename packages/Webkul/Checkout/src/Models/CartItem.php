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

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }

    /**
     * Get the child item.
     */
    public function child()
    {
        return $this->belongsTo(self::class, 'id', 'parent_id');
    }
}
