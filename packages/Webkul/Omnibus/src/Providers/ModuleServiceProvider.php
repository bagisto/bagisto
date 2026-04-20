<?php

namespace Webkul\Omnibus\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Webkul\Omnibus\Models\OmnibusPrice;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        OmnibusPrice::class,
    ];
}
