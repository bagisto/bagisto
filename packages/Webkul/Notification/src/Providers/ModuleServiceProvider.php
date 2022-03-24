<?php

namespace Webkul\Notification\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Notification\Models\Notification::class
    ];
}