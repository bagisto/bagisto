<?php

namespace Webkul\Product\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\Product\Listeners\AttributeFamily;
use Webkul\Product\Listeners\Category;
use Webkul\Product\Listeners\Import;
use Webkul\Product\Listeners\InventorySource;
use Webkul\Product\Listeners\Order;
use Webkul\Product\Listeners\Product;
use Webkul\Product\Listeners\Refund;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.product.create.after' => [
            [Product::class, 'afterCreate'],
        ],

        'catalog.product.update.after' => [
            [Product::class, 'afterUpdate'],
        ],

        'catalog.product.delete.before' => [
            [Product::class, 'beforeDelete'],
        ],

        'catalog.category.update.after' => [
            [Category::class, 'afterUpdate'],
        ],

        'catalog.category.delete.after' => [
            [Category::class, 'afterDelete'],
        ],

        'catalog.attribute_family.update.after' => [
            [AttributeFamily::class, 'afterUpdate'],
        ],

        'data_transfer.imports.batch.indexing.after' => [
            [Import::class, 'afterBatchIndexing'],
        ],

        'inventory.inventory_source.delete.after' => [
            [InventorySource::class, 'afterDelete'],
        ],

        'checkout.order.save.after' => [
            [Order::class, 'afterCancelOrCreate'],
        ],

        'sales.order.cancel.after' => [
            [Order::class, 'afterCancelOrCreate'],
        ],

        'sales.refund.save.after' => [
            [Refund::class, 'afterCreate'],
        ],
    ];
}
