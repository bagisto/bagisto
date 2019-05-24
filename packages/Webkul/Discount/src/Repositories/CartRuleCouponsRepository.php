<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;

class CartRuleCouponsRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CartRuleCoupons';
    }
}