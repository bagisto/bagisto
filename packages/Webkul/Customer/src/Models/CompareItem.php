<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Contracts\CompareItem as CompareItemContract;
use Webkul\Product\Models\ProductProxy;

class CompareItem extends Model implements CompareItemContract
{
    /**
     * Guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'compare_items';

    /**
     * The customer that belong to the compare product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(CustomerProxy::modelClass(), 'customer_id');
    }

    /**
     * The product that belong to the compare product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass(), 'product_id');
    }
}
