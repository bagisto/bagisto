<?php

namespace Webkul\CustomerDocument\Providers;

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
            $viewRenderEventManager->addTemplate('customerdocument::style');
        });

        Event::listen('bagisto.admin.customer.edit.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('customerdocument::admin.customers.upload');
        });
    }
}