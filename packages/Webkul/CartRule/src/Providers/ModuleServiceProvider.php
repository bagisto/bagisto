<?php

namespace Webkul\CartRule\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\CartRule\Models\CartRule::class,
        \Webkul\CartRule\Models\CartRuleTranslation::class,
        \Webkul\CartRule\Models\CartRuleCustomer::class,
        \Webkul\CartRule\Models\CartRuleCoupon::class,
        \Webkul\CartRule\Models\CartRuleCouponUsage::class
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