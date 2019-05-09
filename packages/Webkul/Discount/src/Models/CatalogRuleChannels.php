<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Discount\Contracts\CatalogRuleChannels as CatalogRuleChannelsContract;

class CatalogRuleChannels extends Model implements CatalogRuleChannelsContract
{
    protected $table = 'catalog_rule_channels';

    protected $guarded = ['created_at', 'updated_at'];
}