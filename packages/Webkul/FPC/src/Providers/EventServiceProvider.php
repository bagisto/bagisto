<?php

namespace Webkul\FPC\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\FPC\Listeners\Category;
use Webkul\FPC\Listeners\Channel;
use Webkul\FPC\Listeners\CoreConfig;
use Webkul\FPC\Listeners\Order;
use Webkul\FPC\Listeners\Page;
use Webkul\FPC\Listeners\Product;
use Webkul\FPC\Listeners\Refund;
use Webkul\FPC\Listeners\Review;
use Webkul\FPC\Listeners\Section;
use Webkul\FPC\Listeners\URLRewrite;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /**
         * Catalog events.
         */
        'catalog.product.create.after' => [
            [Product::class, 'afterCreate'],
        ],

        'catalog.product.update.after' => [
            [Product::class, 'afterUpdate'],
        ],

        'catalog.product.delete.before' => [
            [Product::class, 'beforeDelete'],
        ],

        'catalog.category.create.after' => [
            [Category::class, 'afterCreate'],
        ],

        'catalog.category.update.after' => [
            [Category::class, 'afterUpdate'],
        ],

        'catalog.category.delete.before' => [
            [Category::class, 'beforeDelete'],
        ],

        /**
         * Customer events.
         */
        'customer.review.update.after' => [
            [Review::class, 'afterUpdate'],
        ],

        'customer.review.delete.before' => [
            [Review::class, 'beforeDelete'],
        ],

        /**
         * Sales events.
         */
        'checkout.order.save.after' => [
            [Order::class, 'afterCancelOrCreate'],
        ],

        'sales.order.cancel.after' => [
            [Order::class, 'afterCancelOrCreate'],
        ],

        'sales.refund.save.after' => [
            [Refund::class, 'afterCreate'],
        ],

        /**
         * CMS events.
         */
        'cms.page.update.after' => [
            [Page::class, 'afterUpdate'],
        ],

        'cms.page.delete.before' => [
            [Page::class, 'beforeDelete'],
        ],

        /**
         * Theme events.
         */
        'section.create.after' => [
            [Section::class, 'afterCreate'],
        ],

        'section.update.after' => [
            [Section::class, 'afterUpdate'],
        ],

        'section.delete.before' => [
            [Section::class, 'beforeDelete'],
        ],

        /**
         * Core events.
         */
        'core.channel.update.after' => [
            [Channel::class, 'afterUpdate'],
        ],

        'core.configuration.save.after' => [
            [CoreConfig::class, 'afterUpdate'],
        ],

        /**
         * Marketing events.
         */
        'marketing.search_seo.url_rewrites.update.after' => [
            [URLRewrite::class, 'afterUpdate'],
        ],

        'marketing.search_seo.url_rewrites.delete.before' => [
            [URLRewrite::class, 'beforeDelete'],
        ],
    ];
}
