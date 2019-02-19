<?php

namespace Webkul\Inventory\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Inventory\Models\InventorySource::class,
    ];
}