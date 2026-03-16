<?php

namespace Webkul\CatalogRule\Providers;

use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\CatalogRule\Models\CatalogRuleProduct;
use Webkul\CatalogRule\Models\CatalogRuleProductPrice;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        CatalogRule::class,
        CatalogRuleProduct::class,
        CatalogRuleProductPrice::class,
    ];
}
