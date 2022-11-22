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
        'catalog.product.create.after'  => [
            'Webkul\Product\Listeners\Product@afterCreate',
        ],
        'catalog.product.update.after'  => [
            'Webkul\Product\Listeners\Product@afterUpdate',
        ],
        'catalog.product.delete.before' => [
            'Webkul\Product\Listeners\Product@beforeDelete',
        ],
        'checkout.order.save.after'     => [
            'Webkul\Product\Listeners\Order@afterCancelOrCreate',
        ],
        'sales.order.cancel.after'      => [
            'Webkul\Product\Listeners\Order@afterCancelOrCreate',
        ],
        'sales.refund.save.after'       => [
            'Webkul\Product\Listeners\Refund@afterCreate',
        ],
    ];
}