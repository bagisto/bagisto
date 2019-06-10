<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRuleProducts as CatalogRuleProductsContract;

class CatalogRuleProducts extends Model implements CatalogRuleProductsContract
{
    protected $table = 'cart_rules_products';

    protected $guarded = ['created_at', 'updated_at'];
}
