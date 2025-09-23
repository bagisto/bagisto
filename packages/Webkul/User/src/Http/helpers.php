<?php

use Webkul\User\Bouncer;
use Webkul\User\Facades\TwoFactorAuthentication as TwoFactorAuthFacade;

if (! function_exists('bouncer')) {
    function bouncer(): Bouncer
    {
        return app()->make(Bouncer::class);
    }
}

if (! function_exists('two_factor_authentication')) {
    function two_factor_authentication()
    {
        return TwoFactorAuthFacade::getFacadeRoot();
    }
}
