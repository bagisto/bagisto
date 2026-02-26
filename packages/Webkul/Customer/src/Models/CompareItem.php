<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Contracts\CompareItem as CompareItemContract;
use Webkul\Customer\Database\Factories\CompareItemFactory;
use Webkul\Product\Models\ProductProxy;

class CompareItem extends Model implements CompareItemContract
{
    use HasFactory;

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

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return CompareItemFactory::new();
    }
}
