<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\Checkout\Contracts\CartItem as CartItemContract;


class CartItem extends Model implements CartItemContract
{
    protected $table = 'cart_items';

    protected $casts = [
        'additional' => 'array',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->hasOne(ProductProxy::modelClass(), 'id', 'product_id');
    }

    public function cart()
    {
        return $this->hasOne(CartProxy::modelClass(), 'id', 'cart_id');
    }

    /**
     * Get the child item.
     */
    public function child()
    {
        return $this->belongsTo(self::class, 'id', 'parent_id');
    }
}
