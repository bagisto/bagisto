<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Customer\Contracts\CompareItem as CompareItemContract;

class CompareItem extends Model implements CompareItemContract
{
    protected $guarded = [];

    protected $table = 'compare_items';

    /**
     * The customer that belong to the compare product.
     */
    public function customer()
    {
        return $this->belongsTo(CustomerProxy::modelClass(), 'customer_id');
    }
}