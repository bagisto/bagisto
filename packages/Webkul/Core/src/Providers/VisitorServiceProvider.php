<?php

namespace Webkul\Core\Providers;

use Illuminate\Http\Request;
use Shetabit\Visitor\Provider\VisitorServiceProvider as BaseVisitorServiceProvider;
use Webkul\Core\Visitor;

class VisitorServiceProvider extends BaseVisitorServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMacroHelpers();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
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
