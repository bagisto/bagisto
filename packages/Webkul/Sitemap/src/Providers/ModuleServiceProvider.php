<?php

namespace Webkul\Sitemap\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Sitemap\Models\Sitemap::class
    ];
}