<?php

namespace Webkul\Core\Repositories;

use Prettus\Repository\Traits\CacheableRepository;
use Webkul\Core\Eloquent\Repository;

class ExchangeRateRepository extends Repository
{
    use CacheableRepository;

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Core\Contracts\CurrencyExchangeRate::class;
    }
}
