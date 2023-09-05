<?php

namespace Webkul\SocialLogin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.shop.customers.login_form_controls.before', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('social_login::shop.customers.session.social-links');
        });
    }
}