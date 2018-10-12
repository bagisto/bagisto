<?php
namespace Webkul\Tax\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Router;

class TaxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'core');

        $router->aliasMiddleware('locale', Locale::class);

        $router->aliasMiddleware('admin', RedirectIfNotAdmin::class);

        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        // $loader = AliasLoader::getInstance();
        // $loader->alias('core', CoreFacade::class);

        // $this->app->singleton('core', function () {
        //     return app()->make(Core::class);
        // });
    }
}