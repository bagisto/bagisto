<?php

namespace Webkul\DataGrid\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\DataGrid\Models\Filter::class,
    ];
}
