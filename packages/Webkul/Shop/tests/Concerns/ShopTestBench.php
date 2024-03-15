<?php

namespace Webkul\Shop\Tests\Concerns;

use Webkul\Checkout\Contracts\Cart;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Faker\Helpers\Customer as CustomerFaker;

trait ShopTestBench
{
    /**
     * Login as customer.
     */
    public function loginAsCustomer(?CustomerContract $customer = null): CustomerContract
    {
        $customer = $customer ?? (new CustomerFaker())->factory()->create();

        $this->actingAs($customer);

        return $customer;
    }

    /**
     * Set the cart data for a guest user.
     */
    public function setCart(Cart $cart): void
    {
        cart()->setCart($cart);

        cart()->putCart($cart);
    }
}
