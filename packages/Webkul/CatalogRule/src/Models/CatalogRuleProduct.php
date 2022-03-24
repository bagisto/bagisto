<?php

namespace Webkul\CatalogRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Customer\Models\CustomerGroupProxy;
use Webkul\CatalogRule\Contracts\CatalogRuleProduct as CatalogRuleProductContract;

class CatalogRuleProduct extends Model implements CatalogRuleProductContract
{
    public $timestamps = false;

    protected $fillable = [
        'starts_from',
        'ends_till',
        'discount_amount',
        'action_type',
        'end_other_rules',
        'sort_order',
        'catalog_rule_id',
        'channel_id',
        'customer_group_id',
        'product_id',
    ];

    /**
     * Get the Catalog Rule that owns the catalog rule.
     */
    public function catalog_rule()
    {
        return $this->belongsTo(CatalogRuleProxy::modelClass(), 'catalog_rule_id');
    }

    /**
     * Get the Product that owns the catalog rule.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass(), 'product_id');
    }

    /**
     * Get the channels that owns the catalog rule.
     */
    public function channel()
    {
        return $this->belongsTo(ChannelProxy::modelClass(), 'channel_id');
    }

    /**
     * Get the customer groups that owns the catalog rule.
     */
    public function customer_group()
    {
        return $this->belongsTo(CustomerGroupProxy::modelClass(), 'customer_group_id');
    }
}
