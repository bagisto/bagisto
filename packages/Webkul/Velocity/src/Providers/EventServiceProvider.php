<?php

namespace Webkul\Velocity\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

/**
 * Event ServiceProvider
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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
            $viewRenderEventManager->addTemplate('velocity::admin.layouts.style');
        });

        Event::listen('checkout.order.save.after', 'Webkul\Velocity\Helpers\Helper@topBrand');

    }
}
