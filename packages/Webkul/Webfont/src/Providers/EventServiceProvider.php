<?php

namespace Webkul\Webfont\Providers;

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
        if (core()->getConfigData('general.design.webfont.status') && core()->getConfigData('general.design.webfont.enable_backend')) {
            Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate('webfont::webfont');
            });
        }

        if (core()->getConfigData('general.design.webfont.status') && core()->getConfigData('general.design.webfont.enable_frontend')) {
            Event::listen('bagisto.shop.layout.head', function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate('webfont::webfont');
            });
        }
    }
}