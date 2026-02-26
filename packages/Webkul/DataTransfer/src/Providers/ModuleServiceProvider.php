<?php

namespace Webkul\DataTransfer\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        \Webkul\DataTransfer\Models\Import::class,
        \Webkul\DataTransfer\Models\ImportBatch::class,
    ];
}
