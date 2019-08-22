<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerGroupProxy;
use Webkul\Discount\Contracts\CatalogRuleCustomerGroups as CatalogRuleCustomerGroupsContract;

class CatalogRuleCustomerGroups extends Model implements CatalogRuleCustomerGroupsContract
{
    protected $table = 'catalog_rule_customer_groups';

    protected $guarded = ['created_at', 'updated_at'];

    public function customer_group()
    {
        return $this->hasOne(CustomerGroupProxy::modelClass(), 'id', 'customer_group_id');
    }
}