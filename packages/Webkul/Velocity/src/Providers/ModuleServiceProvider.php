<?php

namespace Webkul\Velocity\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Velocity\Models\Category::class,
        \Webkul\Velocity\Models\Content::class,
        \Webkul\Velocity\Models\ContentTranslation::class,
        \Webkul\Velocity\Models\OrderBrand::class,
        \Webkul\Velocity\Models\VelocityCustomerCompareProduct::class,
        \Webkul\Velocity\Models\VelocityMetadata::class,
    ];

    public function boot()
    {
        if ($this->areMigrationsEnabled()) {
            $this->registerMigrations();
        }

        if ($this->areModelsEnabled()) {
            $this->registerModels();
            $this->registerEnums();
            $this->registerRequestTypes();
        }

        if ($this->areViewsEnabled()) {
            $this->registerViews();
        }

        if ($routes = $this->config('routes', true)) {
            $this->registerRoutes($routes);
        }
    }
}