<?php

namespace Webkul\Marketing\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\Marketing\Listeners\Category;
use Webkul\Marketing\Listeners\Page;
use Webkul\Marketing\Listeners\Product;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /**
         * Product events.
         */
        'catalog.product.update.before' => [
            [Product::class, 'beforeUpdate'],
        ],

        'catalog.product.delete.before' => [
            [Product::class, 'beforeDelete'],
        ],

        /**
         * Category events.
         */
        'catalog.category.create.after' => [
            [Category::class, 'afterCreate'],
        ],

        'catalog.category.update.before' => [
            [Category::class, 'beforeUpdate'],
        ],

        'catalog.category.delete.before' => [
            [Category::class, 'beforeDelete'],
        ],

        /**
         * CMS page events.
         */
        'cms.page.create.after' => [
            [Page::class, 'afterCreate'],
        ],

        'cms.page.update.before' => [
            [Page::class, 'beforeUpdate'],
        ],

        'cms.page.delete.before' => [
            [Page::class, 'beforeDelete'],
        ],
    ];
}
