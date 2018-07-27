<?php

namespace Webkul\User\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Webkul\User\Bouncer;
use Webkul\User\Facades\Bouncer as BouncerFacade;
use Webkul\User\Http\Middleware\RedirectIfNotAdmin;
use Webkul\User\ACLCreator;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('admin', RedirectIfNotAdmin::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->createACL();
    }

    /**
     * This method fires an event for acl creation, any package can add their acl item by listening to the admin.acl.build event
     *
     * @return void
     */
    public function createACL()
    {
        Event::listen('admin.acl.create', function () {
            return ACLCreator::create(function ($acl) {
                Event::fire('admin.acl.build', $acl);
            });
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBouncer();

        Event::listen('admin.acl.build', function ($acl) {
            $acl->add('settings.users.roles1', 'Roles1', 'admin.roles.index1', 3);
        });
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerBouncer()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Bouncer', BouncerFacade::class);

        $this->app->singleton('bouncer', function () {
            return new Bouncer();
        });
    }
}
