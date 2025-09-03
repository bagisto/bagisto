<?php

namespace Webkul\DataGrid\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        \Webkul\DataGrid\Models\SavedFilter::class,
    ];
}
