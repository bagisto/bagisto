<?php

namespace Webkul\Channel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Webkul\Channel\Channel;
use Webkul\Channel\Facades\ChannelFacade;

class ChannelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerChannelFacade();
    }
    
    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerChannelFacade()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('channel', ChannelFacade::class);

        $this->app->singleton('channel', function () {
            return new Channel();
        });
    }
}