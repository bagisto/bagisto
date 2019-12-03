<?php

namespace Webkul\CatalogRule\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CatalogRule\Contracts\CatalogRule as CatalogRuleContract;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Customer\Models\CustomerGroupProxy;

class CatalogRule extends Model implements CatalogRuleContract
{
    protected $fillable = [];

    protected $casts = [
        'conditions' => 'array',
    ];
}