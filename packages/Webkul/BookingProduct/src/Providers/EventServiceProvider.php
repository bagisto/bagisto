<?php

namespace Webkul\BookingProduct\Providers;

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
        Event::listen('bagisto.shop.products.view.short_description.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('bookingproduct::shop.products.view.booking');
        });

        Event::listen('checkout.order.save.after', 'Webkul\BookingProduct\Listeners\Order@afterPlaceOrder');
    }
}