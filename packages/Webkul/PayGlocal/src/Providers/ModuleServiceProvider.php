<?php

namespace Webkul\PayGlocal\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Webkul\PayGlocal\Models\PayGlocalTransaction;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        PayGlocalTransaction::class,
    ];
}
