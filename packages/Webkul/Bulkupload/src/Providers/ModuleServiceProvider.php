<?php

namespace Webkul\Bulkupload\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Bulkupload\Models\ImportNewProductsByAdmin::class,
        \Webkul\Bulkupload\Models\DataFlowProfile::class,
    ];
}