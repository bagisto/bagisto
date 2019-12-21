<?php

namespace Webkul\CatalogRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CatalogRule\Contracts\CatalogRuleProduct as CatalogRuleProductContract;

class CatalogRuleProduct extends Model implements CatalogRuleProductContract
{
    public $timestamps = false;

    protected $fillable = ['starts_from', 'ends_till', 'discount_amount', 'action_type', 'end_other_rules', 'sort_order', 'catalog_rule_id', 'channel_id', 'customer_group_id', 'product_id'];
}