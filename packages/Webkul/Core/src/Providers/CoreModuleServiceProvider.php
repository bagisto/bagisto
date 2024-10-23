<?php

namespace Webkul\Core\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

/**
 * This is the overridden `CoreModuleServiceProvider` class from the `konekt/concord` package.
 */
class CoreModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
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
