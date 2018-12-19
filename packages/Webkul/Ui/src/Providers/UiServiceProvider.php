<?php

namespace Webkul\Ui\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class UiServiceProvider extends ServiceProvider
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
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/ui/assets'),
        ], 'public');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'ui');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'ui');

        Paginator::defaultView('ui::partials.pagination');
        Paginator::defaultSimpleView('ui::partials.pagination');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('datagrid', 'Webkul\Ui\DataGrid\DataGrid');
    }
}
