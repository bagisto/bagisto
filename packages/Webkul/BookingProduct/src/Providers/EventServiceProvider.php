<?php

namespace Webkul\BookingProduct\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\Theme\ViewRenderEventManager;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.shop.products.view.short_description.after', static function(ViewRenderEventManager $viewRenderEventManager) {
            if (View::exists('bookingproduct::shop.' . core()->getCurrentChannel()->theme . '.products.view.booking')) {
                $viewRenderEventManager->addTemplate('bookingproduct::shop.' . core()->getCurrentChannel()->theme . '.products.view.booking');
            }
        });

        Event::listen('checkout.order.save.after', 'Webkul\BookingProduct\Listeners\Order@afterPlaceOrder');
    }
}