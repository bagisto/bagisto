<?php

namespace Webkul\Tax\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        TaxCategory::class,
        TaxMap::class,
        TaxRate::class,
    ];
}
