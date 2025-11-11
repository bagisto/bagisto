<?php

namespace Webkul\Razorpay\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Razorpay\Contracts\RazorpayEvent;

class RazorpayEventRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RazorpayEvent::class;
    }
}
