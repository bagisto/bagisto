<?php

namespace Webkul\MagicAI\Facades;

use Illuminate\Support\Facades\Facade;
use Webkul\MagicAI\MagicAI as BaseMagicAI;

class MagicAI extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BaseMagicAI::class;
    }
}
