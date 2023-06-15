<?php

namespace Webkul\Sitemap\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Sitemap\Models\Sitemap::class,
    ];
}