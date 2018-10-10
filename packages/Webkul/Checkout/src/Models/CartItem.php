<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;


class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $casts = [
        'additional' => 'array',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function product() {
        return $this->hasOne('Webkul\Product\Models\Product', 'id', 'product_id');
    }

    // /**
    //  * Get the parent item
    //  */
    // public function parent()
    // {
    //     return $this->hasOne(self::class, 'parent_id', 'id');
    // }

    /**
     * Get the child item.
     */
    public function child()
    {
        return $this->belongsTo(self::class, 'id', 'parent_id');
    }
}
