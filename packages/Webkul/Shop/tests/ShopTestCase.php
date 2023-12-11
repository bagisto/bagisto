<?php

namespace Webkul\Shop\Tests;

use Tests\TestCase;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Faker\Helpers\Customer as CustomerFaker;

class ShopTestCase extends TestCase
{
    /**
     * Login as customer.
     */
    public function loginAsCustomer(CustomerContract $customer = null): CustomerContract
    {
        $customer = $customer ?? (new CustomerFaker())->factory()->create();

        $this->actingAs($customer);

        return $customer;
    }
}
