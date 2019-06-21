<?php

namespace Webkul\StripeConnect\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use View;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.shop.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('stripe::checkout.style');
        });

        Event::listen('bagisto.shop.checkout.payment-method.after', function($viewRenderEventManager){
            $viewRenderEventManager->addTemplate('stripe::checkout.card');
        });

        Event::listen('bagisto.shop.layout.body.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('stripe::checkout.card-script');
        });
    }
}