<?php

namespace Webkul\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Core\Mail\Transport\DynamicSmtpTransport;

class DynamicSmtpServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        $this->app['mail.manager']->extend('bagisto-dynamic-smtp', function () {
            return new DynamicSmtpTransport;
        });
    }
}
