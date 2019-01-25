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
        Event::listen('after.attribute.updated', 'Webkul\Product\Listeners\ProductsFlat@afterAttributeUpdated');

        Event::listen('after.attribute.created', 'Webkul\Product\Listeners\ProductsFlat@afterAttributeCreated');

        Event::listen('after.attribute.deleted', 'Webkul\Product\Listeners\ProductsFlat@afterAttributeDeleted');
    }
}