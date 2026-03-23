<?php

namespace Webkul\Omnibus\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Webkul\Omnibus\Models\OmnibusPrice;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        OmnibusPrice::class,
    ];
}
