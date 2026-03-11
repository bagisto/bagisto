<?php

namespace Webkul\Notification\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\Notification\Models\Notification;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Notification::class,
    ];
}
