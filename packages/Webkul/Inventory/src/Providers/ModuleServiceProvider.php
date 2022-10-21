<?php

namespace Webkul\Inventory\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Inventory\Models\InventorySource::class,
    ];
}
