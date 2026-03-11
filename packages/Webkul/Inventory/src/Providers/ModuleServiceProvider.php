<?php

namespace Webkul\Inventory\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\Inventory\Models\InventorySource;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        InventorySource::class,
    ];
}
