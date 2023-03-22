<?php

namespace Webkul\Category\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.category.create.after'  => [
            'Webkul\Category\Listeners\Category@afterCreate',
        ],

        'catalog.category.update.after'  => [
            'Webkul\Category\Listeners\Category@afterUpdate',
        ],
    ];
}
