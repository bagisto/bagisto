<?php

namespace Webkul\Custom\Providers;

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
        Event::listen('bagisto.admin.settings.slider.create.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('custom::customslider');
        });

        // Event::listen('bagisto.admin.settings.slider.create.after', 'Webkul\Custom\Listeners\CustomSliderEventsHandler@createAfter');

        Event::listen('bagisto.admin.settings.slider.edit.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('custom::customslider');
        });
    }
}