<?php

namespace Webkul\PreOrder\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\PreOrder\Models\PreOrderItem::class,
    ];
}