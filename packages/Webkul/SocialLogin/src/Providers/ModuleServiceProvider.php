<?php

namespace Webkul\SocialLogin\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\SocialLogin\Models\CustomerSocialAccount::class,
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