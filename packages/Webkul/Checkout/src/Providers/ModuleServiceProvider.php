<?php

namespace Webkul\Checkout\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Checkout\Models\Cart::class,
        \Webkul\Checkout\Models\CartAddress::class,
        \Webkul\Checkout\Models\CartItem::class,
        \Webkul\Checkout\Models\CartPayment::class,
        \Webkul\Checkout\Models\CartShippingRate::class,
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