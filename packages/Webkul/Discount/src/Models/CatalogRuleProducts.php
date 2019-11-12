<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRuleProducts as CatalogRuleProductsContract;
use Webkul\Discount\Models\CatalogRuleChannelsProxy as CatalogRuleChannels;
use Webkul\Discount\Models\CatalogRuleCustomerGroupsProxy as CatalogRuleCustomerGroups;

class CatalogRuleProducts extends Model implements CatalogRuleProductsContract
{
    protected $table = 'catalog_rule_products';

    protected $fillable = ['catalog_rule_id', 'starts_from', 'ends_till', 'customer_group_id', 'channel_id', 'product_id', 'action_code', 'action_amount'];
}