<?php

namespace Webkul\PayU\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\PayU\Contracts\PayUTransaction;

class PayUTransactionRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return PayUTransaction::class;
    }
}
