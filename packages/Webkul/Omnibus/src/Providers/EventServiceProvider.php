<?php

namespace Webkul\Omnibus\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('catalog.product.create.after', 'Webkul\Omnibus\Listeners\ProductPriceChange@afterSave');
        Event::listen('catalog.product.update.after', 'Webkul\Omnibus\Listeners\ProductPriceChange@afterSave');

        Event::listen('promotions.catalog_rule.update.after', 'Webkul\Omnibus\Listeners\CatalogRuleChange@afterSave');
        Event::listen('promotions.catalog_rule.create.after', 'Webkul\Omnibus\Listeners\CatalogRuleChange@afterSave');
    }
}
