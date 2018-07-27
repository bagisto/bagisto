<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        View::composer('admin::users.users.index', 'Webkul\Admin\Http\ViewComposers\DataGrids\UserComposer');

        // Using Closure based composers...

        // View::composer('admin::users.index', function ($view) {
        //     //
        // });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
