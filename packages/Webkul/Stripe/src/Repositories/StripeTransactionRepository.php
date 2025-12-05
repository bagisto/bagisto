<?php

namespace Webkul\Stripe\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Stripe\Contracts\StripeTransaction;

class StripeTransactionRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return StripeTransaction::class;
    }
}
