<?php

namespace Webkul\Omnibus\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\Omnibus\Listeners\ProductPriceChange;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.product.create.after' => [
            ProductPriceChange::class,
        ],

        'catalog.product.update.after' => [
            ProductPriceChange::class,
        ],
    ];
}
