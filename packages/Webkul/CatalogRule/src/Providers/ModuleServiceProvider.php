<?php

namespace Webkul\CatalogRule\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\CatalogRule\Models\CatalogRule::class,
        \Webkul\CatalogRule\Models\CatalogRuleProductPrice::class
    ];
}