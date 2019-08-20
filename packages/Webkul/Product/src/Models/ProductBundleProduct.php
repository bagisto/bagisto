<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductBundleProduct as ProductBundleProductContract;

class ProductBundleProduct extends Model implements ProductBundleProductContract
{
    public $timestamps = false;
    
    protected $fillable = ['qty', 'sort_order', 'product_bundle_option_id', 'product_id'];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}