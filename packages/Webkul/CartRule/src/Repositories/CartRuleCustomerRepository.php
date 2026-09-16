<?php

namespace Webkul\CartRule\Repositories;

use Webkul\CartRule\Contracts\CartRuleCustomer;
use Webkul\Core\Eloquent\Repository;

class CartRuleCustomerRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CartRuleCustomer::class;
    }
}
