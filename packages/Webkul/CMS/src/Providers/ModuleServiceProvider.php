<?php

namespace Webkul\CMS\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\CMS\Models\Page::class,
        \Webkul\CMS\Models\PageTranslation::class,
    ];
}
