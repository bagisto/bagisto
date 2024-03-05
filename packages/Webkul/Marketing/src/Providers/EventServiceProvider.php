<?php

namespace Webkul\Marketing\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /**
         * Product Events
         */
        'catalog.product.update.before'  => [
            'Webkul\Marketing\Listeners\Product@beforeUpdate',
        ],

        'catalog.product.delete.before' => [
            'Webkul\Marketing\Listeners\Product@beforeDelete',
        ],

        /**
         * Category Events
         */
        'catalog.category.create.after' => [
            'Webkul\Marketing\Listeners\Category@afterCreate',
        ],

        'catalog.category.update.before' => [
            'Webkul\Marketing\Listeners\Category@beforeUpdate',
        ],

        'catalog.category.delete.before' => [
            'Webkul\Marketing\Listeners\Category@beforeDelete',
        ],

        /**
         * CMS Page Events
         */
        'cms.page.create.after' => [
            'Webkul\Marketing\Listeners\Page@afterCreate',
        ],

        'cms.page.update.before' => [
            'Webkul\Marketing\Listeners\Page@beforeUpdate',
        ],

        'cms.page.delete.before' => [
            'Webkul\Marketing\Listeners\Page@beforeDelete',
        ],
    ];
}
