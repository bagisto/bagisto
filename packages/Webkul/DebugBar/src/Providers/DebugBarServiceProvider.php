<?php

namespace Webkul\DebugBar\Providers;

use Illuminate\Support\ServiceProvider;
use Debugbar;

class DebugBarServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Debugbar::addCollector(app(\Webkul\DebugBar\DataCollector\ModuleCollector::class));
    }
}