<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRuleProducts as CatalogRuleProductContract;
use Webkul\Discount\Models\CatalogRuleChannelsProxy as CatalogRuleChannels;
use Webkul\Discount\Models\CatalogRuleCustomerGroupsProxy as CatalogRuleCustomerGroups;

class CatalogRuleProducts extends Model implements CatalogRuleProductContract
{
    protected $table = 'catalog_rules_products';

    protected $guarded = ['created_at', 'updated_at'];
}