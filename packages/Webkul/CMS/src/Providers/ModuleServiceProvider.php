<?php

namespace Webkul\CMS\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        \Webkul\CMS\Models\Page::class,
        \Webkul\CMS\Models\PageTranslation::class,
    ];
}
