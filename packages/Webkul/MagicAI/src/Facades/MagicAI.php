<?php

namespace Webkul\MagicAI\Facades;

use Illuminate\Support\Facades\Facade;

class MagicAI extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'magic_ai';
    }
}
