<?php

use Webkul\User\Bouncer;
use Webkul\User\Facades\Bouncer as BouncerFacade;

if (! function_exists('bouncer')) {
    /**
     * Bouncer helper.
     *
     * @return Bouncer
     */
    function bouncer()
    {
        return BouncerFacade::getFacadeRoot();
    }
}
