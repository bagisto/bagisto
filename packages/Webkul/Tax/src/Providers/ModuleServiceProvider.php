<?php

namespace Webkul\Tax\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Tax\Models\TaxCategory::class,
        \Webkul\Tax\Models\TaxMap::class,
        \Webkul\Tax\Models\TaxRate::class,
    ];
}