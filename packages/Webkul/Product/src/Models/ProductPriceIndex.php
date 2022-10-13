<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerGroupProxy;
use Webkul\Product\Contracts\ProductPriceIndex as ProductPriceIndexContract;

class ProductPriceIndex extends Model implements ProductPriceIndexContract
{
    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'min_price',
        'regular_min_price',
        'max_price',
        'regular_max_price',
        'product_id',
        'customer_group_id',
    ];

    /**
     * Get the product that owns the price index.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the customer group that owns the price index.
     */
    public function customer_group()
    {
        return $this->belongsTo(CustomerGroupProxy::modelClass());
    }
}
