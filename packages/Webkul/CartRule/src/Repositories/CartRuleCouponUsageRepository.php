<?php

namespace Webkul\CartRule\Repositories;

use Webkul\CartRule\Contracts\CartRuleCouponUsage;
use Webkul\Core\Eloquent\Repository;

class CartRuleCouponUsageRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CartRuleCouponUsage::class;
    }
}
