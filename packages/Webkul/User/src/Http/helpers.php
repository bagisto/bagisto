<?php

use Webkul\User\Bouncer;
use Webkul\User\TwoFactorAuthentication;

if (! function_exists('bouncer')) {
    function bouncer(): Bouncer
    {
        return app()->make(Bouncer::class);
    }
}

if (! function_exists('twoFactorAuth')) {
    function twoFactorAuth(): TwoFactorAuthentication
    {
        return app()->make(TwoFactorAuthentication::class);
    }
}