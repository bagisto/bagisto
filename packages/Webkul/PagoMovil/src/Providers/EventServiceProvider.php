<?php

namespace Webkul\PagoMovil\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen('bagisto.admin.sales.order.payment-method.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('pagomovil::admin.payment_details');
        });
    }
}
