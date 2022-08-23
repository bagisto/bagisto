<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;
use Prettus\Repository\Traits\CacheableRepository;

class CountryStateRepository extends Repository
{
    use CacheableRepository;

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