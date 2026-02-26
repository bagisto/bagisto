<?php

namespace Webkul\Core\Providers;

use Illuminate\Http\Request;
use Shetabit\Visitor\Provider\VisitorServiceProvider as BaseVisitorServiceProvider;
use Webkul\Core\Visitor;

/**
 * This is the overridden `VisitorServiceProvider` class from the `shetabit/visitor` package.
 */
class VisitorServiceProvider extends BaseVisitorServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
        /**
         * Bind to service container.
         */
        $this->app->singleton('shetabit-visitor', function () {
            $request = app(Request::class);

            return new Visitor($request, config('visitor'));
        });
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->registerMacroHelpers();
    }
}
