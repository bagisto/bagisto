<?php

namespace Webkul\DebugBar\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\DebugBar\DataCollector\ModuleCollector;
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
        Debugbar::addCollector(app(ModuleCollector::class));
    }
}