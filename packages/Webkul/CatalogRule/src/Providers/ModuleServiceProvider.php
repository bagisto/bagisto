<?php

namespace Webkul\CatalogRule\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        \Webkul\CatalogRule\Models\CatalogRule::class,
        \Webkul\CatalogRule\Models\CatalogRuleProduct::class,
        \Webkul\CatalogRule\Models\CatalogRuleProductPrice::class,
    ];
}
