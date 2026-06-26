<?php

namespace Webkul\Marketplace\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen('checkout.order.save.after', 'Webkul\Marketplace\Listeners\OrderPlaced@handle');
    }
}
