<?php

namespace Webkul\Payment\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Payment\Listeners\GenerateInvoice;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('checkout.order.save.after', [GenerateInvoice::class, 'handle']);
    }
}
