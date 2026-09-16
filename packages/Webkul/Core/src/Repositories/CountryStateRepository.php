<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Contracts\CountryState;
use Webkul\Core\Eloquent\Repository;

class CountryStateRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CountryState::class;
    }
}
