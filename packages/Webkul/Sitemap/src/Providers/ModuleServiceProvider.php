<?php

namespace Webkul\Sitemap\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\Sitemap\Models\Sitemap;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Sitemap::class,
    ];
}
