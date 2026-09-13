<?php

namespace Webkul\Shop\Tests\Concerns;

use Webkul\Core\Models\Channel;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Customer\Models\Customer;

trait AuthHelpers
{
    /**
     * Login as a shop customer.
     */
    public function loginAsCustomer(?CustomerContract $customer = null): CustomerContract
    {
        $customer = $customer ?? Customer::factory()->create();

        $this->actingAs($customer);

        return $customer;
    }

    /**
     * Log in a customer of the given group, or keep browsing as a guest when no group is given.
     */
    public function actAsCustomerGroup(?int $customerGroupId): ?CustomerContract
    {
        if (is_null($customerGroupId)) {
            return null;
        }

        return $this->loginAsCustomer(Customer::factory()->create([
            'customer_group_id' => $customerGroupId,
        ]));
    }

    /**
     * Ensure the request is treated as a guest (no auth).
     */
    public function asGuest(): static
    {
        auth()->guard('customer')->logout();

        return $this;
    }

    /**
     * Simulate requests to a specific channel via hostname.
     */
    public function forChannel(Channel $channel): static
    {
        $hostname = $channel->hostname
            ? parse_url($channel->hostname, PHP_URL_HOST) ?? $channel->hostname
            : 'localhost';

        $this->withServerVariables(['HTTP_HOST' => $hostname]);

        return $this;
    }
}
