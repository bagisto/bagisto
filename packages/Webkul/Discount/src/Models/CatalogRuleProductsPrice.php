<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRuleProductsPrice as CatalogRuleProductsPriceContract;

class CatalogRuleProductsPrice extends Model implements CatalogRuleProductsPriceContract
{
    protected $table = 'catalog_rules_products_price';

    protected $fillable = ['catalog_rule_id', 'starts_from', 'ends_till', 'customer_group_id', 'channel_id', 'product_id', 'rule_price'];
}