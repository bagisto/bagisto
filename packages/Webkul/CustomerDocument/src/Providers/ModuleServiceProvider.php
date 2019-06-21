<?php

namespace Webkul\CustomerDocument\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\CustomerDocument\Models\CustomerDocument::class,
    ];
}