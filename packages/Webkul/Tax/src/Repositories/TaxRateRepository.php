<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Tax\Contracts\TaxRate;

class TaxRateRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return TaxRate::class;
    }
}
