<?php

namespace Webkul\FPC\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.product.update.after'  => [
            'Webkul\FPC\Listeners\Product@afterUpdate',
        ],

        'catalog.product.delete.before' => [
            'Webkul\FPC\Listeners\Product@beforeDelete',
        ],

        'catalog.category.update.after' => [
            'Webkul\FPC\Listeners\Category@afterUpdate',
        ],

        'catalog.category.delete.before' => [
            'Webkul\FPC\Listeners\Category@beforeDelete',
        ],

        'customer.review.update.after' => [
            'Webkul\FPC\Listeners\Review@afterUpdate',
        ],

        'customer.review.delete.before' => [
            'Webkul\FPC\Listeners\Review@beforeDelete',
        ],

        'checkout.order.save.after'     => [
            'Webkul\FPC\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.order.cancel.after'      => [
            'Webkul\FPC\Listeners\Order@afterCancelOrCreate',
        ],

        'sales.refund.save.after'       => [
            'Webkul\FPC\Listeners\Refund@afterCreate',
        ],

        'cms.page.update.after' => [
            'Webkul\FPC\Listeners\Page@afterUpdate',
        ],

        'cms.page.delete.before' => [
            'Webkul\FPC\Listeners\Page@beforeDelete',
        ],

        'theme_customization.create.after' => [
            'Webkul\FPC\Listeners\ThemeCustomization@afterCreate',
        ],

        'theme_customization.update.after' => [
            'Webkul\FPC\Listeners\ThemeCustomization@afterUpdate',
        ],

        'theme_customization.delete.before' => [
            'Webkul\FPC\Listeners\ThemeCustomization@beforeDelete',
        ],

        'core.channel.update.after' => [
            'Webkul\FPC\Listeners\Channel@afterUpdate',
        ],

        'marketing.search_seo.url_rewrites.update.after' => [
            'Webkul\FPC\Listeners\URLRewrite@afterUpdate',
        ],

        'marketing.search_seo.url_rewrites.delete.before' => [
            'Webkul\FPC\Listeners\URLRewrite@beforeDelete',
        ],
    ];
}
