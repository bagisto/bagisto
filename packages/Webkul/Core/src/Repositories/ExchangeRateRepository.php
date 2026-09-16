<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Contracts\CurrencyExchangeRate;
use Webkul\Core\Eloquent\Repository;

class ExchangeRateRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CurrencyExchangeRate::class;
    }
}
