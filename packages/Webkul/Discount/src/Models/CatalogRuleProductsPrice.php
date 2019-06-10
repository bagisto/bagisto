<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRuleProductsPrice as CatalogRuleProductsPriceContract;

class CatalogRuleProductsPrice extends Model implements CatalogRuleProductsPriceContract
{
    protected $table = 'cart_rules_products_price';

    protected $guarded = ['created_at', 'updated_at'];
}
