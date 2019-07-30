<?php

namespace Webkul\PreOrder\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('preorder::admin.layouts.style');
        });

        Event::listen('bagisto.shop.products.view.short_description.before', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('preorder::shop.products.preorder-info');
        });

        Event::listen('checkout.cart.add.before', 'Webkul\PreOrder\Listeners\Cart@cartItemAddBefore');

        Event::listen('checkout.cart.update.before', 'Webkul\PreOrder\Listeners\Cart@cartItemUpdateBefore');

        Event::listen('checkout.cart.add.after', 'Webkul\PreOrder\Listeners\Cart@cartItemAddAfter');

        Event::listen('checkout.order.save.after', 'Webkul\PreOrder\Listeners\Order@afterPlaceOrder');

        Event::listen('sales.invoice.save.after', 'Webkul\PreOrder\Listeners\Invoice@afterInvoice');
    }
}