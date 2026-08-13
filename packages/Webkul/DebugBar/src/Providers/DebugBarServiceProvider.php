<?php

namespace Webkul\DebugBar\Providers;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\ServiceProvider;
use Webkul\DebugBar\DataCollector\ModuleCollector;

class DebugBarServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (! class_exists(Debugbar::class)) {
            return;
        }

        /**
         * The collector keeps every query, view and model it is told about for as long as the
         * process lives, which is what drawing a bar for one request needs and what a command
         * cannot afford: an indexer or a queue worker would grow until something killed it.
         */
        if ($this->app->runningInConsole()) {
            return;
        }

        if (! Debugbar::isEnabled()) {
            return;
        }

        Debugbar::addCollector(app(ModuleCollector::class));
    }
}
