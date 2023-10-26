<?php

namespace Webkul\Core\Providers;

use Shetabit\Visitor\Provider\VisitorServiceProvider as BaseVisitorServiceProvider;

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
}
