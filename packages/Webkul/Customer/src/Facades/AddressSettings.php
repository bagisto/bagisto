<?php

namespace Webkul\Customer\Facades;

use Illuminate\Support\Facades\Facade;
use Webkul\Customer\AddressSettings as BaseAddressSettings;

class AddressSettings extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return BaseAddressSettings::class;
    }
}
