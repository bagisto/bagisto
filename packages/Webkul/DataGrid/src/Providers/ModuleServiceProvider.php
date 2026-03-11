<?php

namespace Webkul\DataGrid\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\DataGrid\Models\SavedFilter;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        SavedFilter::class,
    ];
}
