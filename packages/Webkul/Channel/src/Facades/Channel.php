<?php

namespace Webkul\Channel\Facades;

use Illuminate\Support\Facades\Facade;

class Channel extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'channel';
    }
}