<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRuleCustomerGroups as CatalogRuleCustomerGroupsContract;
use Webkul\Discount\Models\CatalogRuleProxy as CatalogRule;

class CatalogRuleCustomerGroups extends Model implements CatalogRuleCustomerGroupsContract
{
    protected $table = 'catalog_rule_customer_groups';

    protected $guarded = ['created_at', 'updated_at'];

    public function catalog_rule()
    {
        return $this->belongsTo(CatalogRule::modelClass(), 'catalog_rule_id');
    }
}