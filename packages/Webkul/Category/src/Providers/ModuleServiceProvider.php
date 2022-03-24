<?php

namespace Webkul\Category\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Category\Models\Category::class,
        \Webkul\Category\Models\CategoryTranslation::class,
    ];
}