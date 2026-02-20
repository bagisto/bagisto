<?php

namespace Webkul\ChannelSmtp\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\ChannelSmtp\Mail\ChannelAwareMailManager;
use Webkul\ChannelSmtp\Mail\ChannelSmtpConfigApplier;

class ChannelSmtpServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'channelsmtp');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->app->singleton(ChannelSmtpConfigApplier::class);

        $this->app->extend('mail.manager', function ($mailManager, $app) {
            return new ChannelAwareMailManager($app, $app->make(ChannelSmtpConfigApplier::class));
        });
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );
    }
}
