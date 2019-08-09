<?php

namespace Webkul\CMS\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\CMS\Models\CMS::class
    ];
}