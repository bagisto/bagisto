<?php

namespace Webkul\Webfont\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Webfont\Models\Webfont::class,
    ];
}