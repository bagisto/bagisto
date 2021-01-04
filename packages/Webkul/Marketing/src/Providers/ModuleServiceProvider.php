<?php

namespace Webkul\Marketing\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Marketing\Models\Campaign::class,
        \Webkul\Marketing\Models\Template::class,
        \Webkul\Marketing\Models\Event::class,
    ];
}