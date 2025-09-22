<?php

namespace Webkul\User\Facades;

use Illuminate\Support\Facades\Facade;
use Webkul\User\TwoFactorAuthentication as BaseTwoFactorAuthentication;
use PragmaRX\Google2FA\Google2FA;


class TwoFactorAuthentication extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return BaseTwoFactorAuthentication::class;
    }
}