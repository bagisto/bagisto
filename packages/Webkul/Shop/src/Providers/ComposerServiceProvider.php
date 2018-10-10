<?php

namespace Webkul\Shop\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Webkul\Product\Product\ProductImage;
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
        View::composer(
            ['shop::layouts.header.index', 'shop::layouts.footer.footer'],
            'Webkul\Shop\Http\ViewComposers\CategoryComposer'
        );

        View::composer(
            ['shop::home.new-products'],
            'Webkul\Shop\Http\ViewComposers\NewProductListComposer'
        );
    }
}