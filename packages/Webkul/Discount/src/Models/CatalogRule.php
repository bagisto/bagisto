<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRule as CatalogRuleContract;
use Webkul\Discount\Models\CatalogRuleChannelsProxy as CatalogRuleChannels;
use Webkul\Discount\Models\CatalogRuleCustomerGroupsProxy as CatalogRuleCustomerGroups;

class CatalogRule extends Model implements CatalogRuleContract
{
    protected $table = 'catalog_rules';

    protected $fillable = ['name', 'description', 'starts_from', 'ends_till', 'status', 'conditions', 'actions', 'end_other_rules', 'action_code', 'discount_amount'];

    public function channels()
    {
        return $this->hasMany(CatalogRuleChannels::modelClass());
    }

    public function customer_groups()
    {
        return $this->hasMany(CatalogRuleCustomerGroups::modelClass());
    }
}