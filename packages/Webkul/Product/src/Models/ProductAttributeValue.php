<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\Attribute;
use Webkul\Product\Models\Product;

class ProductAttributeValue extends Model
{
    /**
     * Get the attribue that owns the attribute value.
     */
    public function attribue()
    {
        return $this->belongsTo(Attribue::class);
    }

    /**
     * Get the product that owns the attribute value.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}