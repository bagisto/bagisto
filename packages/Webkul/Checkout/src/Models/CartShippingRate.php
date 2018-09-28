<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Checkout\Models\CartAddress;

class CartShippingRate extends Model
{
    /**
     * Get the post that owns the comment.
     */
    public function shipping_address()
    {
        return $this->belongsTo(CartAddress::class);
    }
}