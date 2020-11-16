<?php

namespace Webkul\Paypal\Providers;

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
        if (core()->getConfigData('sales.paymentmethods.paypal_smart_button.active')) { 
            Event::listen('bagisto.shop.layout.body.after', static function(ViewRenderEventManager $viewRenderEventManager) {
                $viewRenderEventManager->addTemplate('paypal::checkout.onepage.paypal-smart-button');
            });
        }
    }
}