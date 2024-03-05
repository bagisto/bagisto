<?php

namespace Webkul\Core\Facades;

use Illuminate\Support\Facades\Facade;

class ElasticSearch extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'elasticsearch';
    }
}
