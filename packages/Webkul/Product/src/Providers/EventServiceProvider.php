<?php

namespace Webkul\Product\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.attribute.create.after' => [
            'Webkul\Product\Listeners\Attribute@removeOrCreateProductFlatColumn',
        ],
        'catalog.attribute.update.after' => [
            'Webkul\Product\Listeners\Attribute@removeOrCreateProductFlatColumn',
        ],
        'catalog.attribute.delete.before' => [
            'Webkul\Product\Listeners\Attribute@removeProductFlatColumn',
        ],
        'catalog.product.create.after' => [
            'Webkul\Product\Listeners\Product@updateOrCreateProductFlatRecord',
        ],
        'catalog.product.update.after' => [
            'Webkul\Product\Listeners\Product@updateOrCreateProductFlatRecord',
            'Webkul\Product\Listeners\Product@reIndexPrice',
        ],
    ];
}
