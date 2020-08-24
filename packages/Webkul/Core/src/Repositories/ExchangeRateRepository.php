<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;

class ExchangeRateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Contracts\CurrencyExchangeRate';
    }
}