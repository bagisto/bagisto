<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductGroupedProduct as ProductGroupedProductContract;

class ProductGroupedProduct extends Model implements ProductGroupedProductContract
{
    public $timestamps = false;
    
    protected $fillable = ['qty', 'sort_order', 'product_id', 'associated_product_id'];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the product that owns the image.
     */
    public function associated_product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}