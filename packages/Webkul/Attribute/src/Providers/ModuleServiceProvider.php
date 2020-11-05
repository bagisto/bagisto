<?php

namespace Webkul\Attribute\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Attribute\Models\Attribute::class,
        \Webkul\Attribute\Models\AttributeFamily::class,
        \Webkul\Attribute\Models\AttributeGroup::class,
        \Webkul\Attribute\Models\AttributeOption::class,
        \Webkul\Attribute\Models\AttributeOptionTranslation::class,
        \Webkul\Attribute\Models\AttributeTranslation::class,
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