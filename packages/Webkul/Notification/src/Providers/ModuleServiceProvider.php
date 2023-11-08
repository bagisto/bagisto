<?php

namespace Webkul\Notification\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Notification\Models\Notification::class,
    ];
}
