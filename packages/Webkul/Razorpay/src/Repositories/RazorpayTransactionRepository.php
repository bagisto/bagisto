<?php

namespace Webkul\Razorpay\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Razorpay\Contracts\RazorpayTransaction;

class RazorpayTransactionRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RazorpayTransaction::class;
    }
}
