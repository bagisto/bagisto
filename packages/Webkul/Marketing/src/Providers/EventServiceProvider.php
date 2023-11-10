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
        'catalog.product.create.before'  => [
            'Webkul\Marketing\Listeners\Product@beforeCreate',
        ],

        'catalog.product.update.after'  => [
            'Webkul\Marketing\Listeners\Product@afterUpdate',
        ],

        'catalog.product.delete.before' => [
            'Webkul\Marketing\Listeners\Product@beforeDelete',
        ],

        /**
         * Category Events
         */
        'catalog.category.create.before' => [
            'Webkul\Marketing\Listeners\Category@beforeCreate',
        ],

        'catalog.category.update.after' => [
            'Webkul\Marketing\Listeners\Category@afterUpdate',
        ],

        'catalog.category.delete.before' => [
            'Webkul\Marketing\Listeners\Category@beforeDelete',
        ],

        /**
         * CMS Page Events
         */
        'cms.page.create.before' => [
            'Webkul\Marketing\Listeners\Page@beforeCreate',
        ],

        'cms.page.update.after' => [
            'Webkul\Marketing\Listeners\Page@afterUpdate',
        ],

        'cms.page.delete.before' => [
            'Webkul\Marketing\Listeners\Page@beforeDelete',
        ],
    ];
}
