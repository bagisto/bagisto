<?php

namespace Webkul\SAASPreOrder\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\SAASPreOrder\Models\PreOrderItem::class,
    ];
}