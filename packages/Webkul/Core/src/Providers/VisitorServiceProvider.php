<?php

namespace Webkul\Core\Providers;

use Illuminate\Http\Request;
use Shetabit\Visitor\Provider\VisitorServiceProvider as BaseVisitorServiceProvider;
use Webkul\Core\Visitor;

class VisitorServiceProvider extends BaseVisitorServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->registerMacroHelpers();
    }

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
}
