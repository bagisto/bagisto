<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Contracts\Country;
use Webkul\Core\Eloquent\Repository;

class CountryRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return Country::class;
    }
}
