<?php

namespace Webkul\GTM\Providers;

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
            $viewRenderEventManager->addTemplate('gtm::head');
        });

        Event::listen('bagisto.shop.layout.body.before', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('gtm::body');
        });
    }
}