<?php

namespace Webkul\BookingProduct\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Webkul\Theme\ViewRenderEventManager;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'checkout.order.save.after' => [
            'Webkul\BookingProduct\Listeners\Order@afterPlaceOrder',
        ],
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('bagisto.shop.products.view.short_description.after', static function (ViewRenderEventManager $viewRenderEventManager) {
            if (View::exists('booking_product::shop.' . core()->getCurrentChannel()->theme . '.products.view.booking')) {
                $viewRenderEventManager->addTemplate('booking_product::shop.' . core()->getCurrentChannel()->theme . '.products.view.booking');
            }
        });
    }
}
