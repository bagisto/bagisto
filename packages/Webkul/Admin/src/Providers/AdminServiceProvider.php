<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Menu;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/admin/assets'),
        ], 'public');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'admin');

        $this->createAdminMenu();
    }

    /**
     * This method fires an event for menu creation, any package can add their menu item by listening to the admin.menu.build event
     *
     * @return void
     */
    public function createAdminMenu()
    {
        Event::listen('admin.menu.create', function() {
            return Menu::create(function($menu) {
                Event::fire('admin.menu.build', $menu);
            });
        });

        Event::listen('admin.menu.build', function($menu) {
            $menu->add('dashboard', 'Dashboard', 'url', 0, '');
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/auth.php', 'auth'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge($config, require $path));
    }
}
