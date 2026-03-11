<?php

namespace Webkul\DataTransfer\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\DataTransfer\Models\Import;
use Webkul\DataTransfer\Models\ImportBatch;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Import::class,
        ImportBatch::class,
    ];
}
