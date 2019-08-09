<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRuleProductsPrice as CatalogRuleProductPriceContract;

class CatalogRuleProductsPrice extends Model implements CatalogRuleProductContract
{
    protected $table = 'catalog_rules_products_price';

    protected $guarded = ['created_at', 'updated_at'];
}