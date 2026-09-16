<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Tax\Contracts\TaxMap;

class TaxMapRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return TaxMap::class;
    }
}
