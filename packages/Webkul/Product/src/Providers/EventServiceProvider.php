<?php

namespace Webkul\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('catalog.attribute.create.after', 'Webkul\Product\Listeners\ProductFlat@afterAttributeCreatedUpdated');

        Event::listen('catalog.attribute.update.after', 'Webkul\Product\Listeners\ProductFlat@afterAttributeCreatedUpdated');

        Event::listen('catalog.attribute.delete.before', 'Webkul\Product\Listeners\ProductFlat@afterAttributeDeleted');

        Event::listen('catalog.product.create.after', 'Webkul\Product\Listeners\ProductFlat@afterProductCreatedUpdated');

        Event::listen('catalog.product.update.after', 'Webkul\Product\Listeners\ProductFlat@afterProductCreatedUpdated');
    }
}