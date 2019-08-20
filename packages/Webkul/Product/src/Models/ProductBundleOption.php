<?php

namespace Webkul\Product\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Contracts\ProductBundleOption as ProductBundleOptionContract;

class ProductBundleOption extends TranslatableModel implements ProductBundleOptionContract
{
    public $timestamps = false;

    public $translatedAttributes = ['label'];

    protected $fillable = ['type', 'is_required', 'sort_order', 'product_id'];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}