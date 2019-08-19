<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\Product\Models\ProductFlatProxy;
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

    /**
     * The Product Flat that belong to the product.
     */
    public function product_flat()
    {
        return (ProductFlatProxy::modelClass())
            ::where('product_flat.product_id', $this->product_id)
            ->where('product_flat.locale', app()->getLocale())
            ->where('product_flat.channel', core()->getCurrentChannelCode())
            ->select('product_flat.*');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getProductFlatAttribute()
    {
        return $this->product_flat()->first();
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

    /**
     * Get the children items.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
