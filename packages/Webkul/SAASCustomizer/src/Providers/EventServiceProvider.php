<?php

namespace Webkul\SAASCustomizer\Providers;

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
        Event::listen('new.company.registered', 'Webkul\SAASCustomizer\Listeners\CompanyRegistered@handle');
    }
}