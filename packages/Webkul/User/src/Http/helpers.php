<?php

use Webkul\User\Facades\Bouncer as BouncerFacade;
use Webkul\User\Facades\TwoFactorAuthentication as TwoFactorAuthenticationFacade;

if (! function_exists('bouncer')) {
    /**
     * Bouncer helper.
     *
     * @return \Webkul\User\Bouncer
     */
    function bouncer()
    {
        return BouncerFacade::getFacadeRoot();
    }
}

if (! function_exists('two_factor_authentication')) {
    function two_factor_authentication()
    {
        return TwoFactorAuthenticationFacade::getFacadeRoot();
    }
}
