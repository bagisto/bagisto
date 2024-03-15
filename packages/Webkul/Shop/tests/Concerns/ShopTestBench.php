<?php

namespace Webkul\Shop\Tests\Concerns;

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
     * Login as customer.
     */
    public function createCartIfNotExists(?CustomerContract $customer = null): void
    {
        if (cart()->getCart()) {
            return;
        }

        $data = [
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'is_guest'              => 1,
        ];

        if ($customer) {
            $data = array_merge($data, [
                'is_guest'            => 0,
                'customer_id'         => $customer->id,
                'customer_first_name' => $customer->first_name,
                'customer_last_name'  => $customer->last_name,
                'customer_email'      => $customer->email,
            ]);
        }

        cart()->createCart($data);
    }
}
