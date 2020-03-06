<?php

namespace Webkul\Velocity\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Velocity\Models\Content::class,
        \Webkul\Velocity\Models\Category::class,
    ];
}