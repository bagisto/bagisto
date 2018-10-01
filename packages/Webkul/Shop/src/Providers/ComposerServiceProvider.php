<?php

namespace Webkul\Shop\Providers;

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
        //using the class based composers...
        View::composer(['shop::layouts.header.index', 'shop::layouts.footer','shop::layouts.master'], 'Webkul\Shop\Http\ViewComposers\Categories\CategoryComposer');
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
