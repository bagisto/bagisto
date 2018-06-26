<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Blade;
use Webkul\Ui\Menu;

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

        $this->composeView();

        Blade::directive('continue', function() { return "<?php continue; ?>"; });

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
            $menu->add('dashboard', 'Dashboard', route('admin.dashboard.index'), 1, 'icon-dashboard');

            $menu->add('configuration', 'Configure', route('admin.account.edit'), 6, 'icon-configuration');

            $menu->add('configuration.account', 'My Account', route('admin.account.edit'), 1, '');

            $menu->add('settings', 'Settings', '', 6, 'icon-settings');

            $menu->add('settings.users', 'Users', route('admin.users.index'), 1, '');

            $menu->add('settings.roles', 'Roles', route('admin.permissions.index'), 2, '');

        });
    }

    /**
     * Bind the the data to the views
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer('admin::layouts.nav-left', function($view) {
            $menu = current(Event::fire('admin.menu.create'));
            $view->with('menu', $menu);
        });

        view()->composer('admin::layouts.nav-aside', function($view) {
            $parentMenu = current(Event::fire('admin.menu.create'));
            $menu = [];
            foreach ($parentMenu->items as $item) {
                $currentKey = current(explode('.', $parentMenu->currentKey));
                if($item['key'] != $currentKey)
                    continue;

                $menu = [
                    'items' => $parentMenu->sortItems($item['children']),
                    'current' => $parentMenu->current,
                    'currentKey' => $parentMenu->currentKey
                ];
            }

            $view->with('menu', $menu);
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
