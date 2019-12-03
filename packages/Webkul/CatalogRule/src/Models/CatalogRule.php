<?php

namespace Webkul\CatalogRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CatalogRule\Contracts\CatalogRule as CatalogRuleContract;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Customer\Models\CustomerGroupProxy;

class CatalogRule extends Model implements CatalogRuleContract
{
    protected $fillable = ['name', 'description', 'starts_from', 'ends_till', 'status', 'condition_type', 'conditions',  'end_other_rules', 'action_type', 'discount_amount', 'sort_order'];

    protected $casts = [
        'conditions' => 'array'
    ];

    /**
     * Get the channels that owns the catalog rule.
     */
    public function channels()
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'catalog_rule_channels');
    }

    /**
     * Get the customer groups that owns the catalog rule.
     */
    public function customer_groups()
    {
        return $this->belongsToMany(CustomerGroupProxy::modelClass(), 'catalog_rule_customer_groups');
    }
}