<?php

namespace Webkul\User\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\User\Models\Admin::class,
        \Webkul\User\Models\Role::class,
    ];
}