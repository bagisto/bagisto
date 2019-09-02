<?php

namespace Webkul\Discount\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class DiscountServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * To merge the price rule configuration in price rule configuration
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/rule-conditions.php', 'pricerules'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/discount-rules.php', 'discount-rules'
        );
    }
}