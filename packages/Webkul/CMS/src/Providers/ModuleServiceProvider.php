<?php

namespace Webkul\CMS\Providers;

use Webkul\CMS\Models\Page;
use Webkul\CMS\Models\PageTranslation;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Page::class,
        PageTranslation::class,
    ];
}
