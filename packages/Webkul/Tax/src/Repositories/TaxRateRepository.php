<?php

namespace Webkul\Tax\Repositories;

use Webkul\Core\Eloquent\Repository;

class TaxRateRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Tax\Contracts\TaxRate';
    }
}
