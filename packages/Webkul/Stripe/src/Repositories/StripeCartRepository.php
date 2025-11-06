<?php

namespace Webkul\Stripe\Repositories;

use Webkul\Core\Eloquent\Repository;

class StripeCartRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\Stripe\Contracts\StripeCart';
    }
}
