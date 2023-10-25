<?php

namespace Webkul\SocialLogin\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.shop.customers.login_form_controls.after', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('social_login::shop.customers.session.social-links');
        });
    }
}
