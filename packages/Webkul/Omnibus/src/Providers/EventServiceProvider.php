<?php

namespace Webkul\Omnibus\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('catalog.product.create.after', 'Webkul\Omnibus\Listeners\ProductPriceChange@afterSave');
        Event::listen('catalog.product.update.after', 'Webkul\Omnibus\Listeners\ProductPriceChange@afterSave');

        Event::listen('bagisto.shop.products.price.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('omnibus::shop.inject');
        });
    }
}
