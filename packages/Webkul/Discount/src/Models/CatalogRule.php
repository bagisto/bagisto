<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRule as CatalogRuleContract;

class CatalogRule extends Model implements CatalogRuleContract
{
    protected $table = 'catalog_rules';

    protected $guarded = ['created_at', 'updated_at'];
}