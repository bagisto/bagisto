<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;

class CountryStateRepository extends Repository
{
    /**
     * @var boolean
     */
    protected $cacheEnabled = true;

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Core\Contracts\CountryState';
    }
}