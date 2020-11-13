<?php

namespace Webkul\CMS\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\CMS\Models\CmsPage::class,
        \Webkul\CMS\Models\CmsPageTranslation::class
    ];
}