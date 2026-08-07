<?php

namespace Webkul\PayGlocal\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\PayGlocal\Contracts\PayGlocalTransaction;

class PayGlocalTransactionRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * The contract, not the model, so that the model stays substitutable through Concord the way
     * core's repositories do.
     */
    public function model(): string
    {
        return PayGlocalTransaction::class;
    }
}
