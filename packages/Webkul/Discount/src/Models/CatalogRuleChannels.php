<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRuleChannels as CatalogRuleChannelsContract;
use Webkul\Discount\Models\CatalogRuleProxy as CatalogRule;

class CatalogRuleChannels extends Model implements CatalogRuleChannelsContract
{
    protected $table = 'catalog_rule_channels';

    protected $guarded = ['created_at', 'updated_at'];

    public function catalog_rule()
    {
        return $this->belongsTo(CatalogRule::modelClass(), 'catalog_rule_id');
    }
}