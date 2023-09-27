<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;

class ExchangeRateRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Core\Contracts\CurrencyExchangeRate';
    }
}
