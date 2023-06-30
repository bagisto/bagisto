<?php

namespace Webkul\SocialShare\Providers;

use Illuminate\Support\ServiceProvider;

class SocialShareServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'SocialShare');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'SocialShare');

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}
