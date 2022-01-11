<?php

namespace Webkul\Checkout\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Checkout\Models\Cart;
use Webkul\Customer\Models\Customer;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $now = date('Y-m-d H:i:s');

        return [
            'is_guest'              => 0,
            'is_active'             => 1,
            'is_gift'               => 0,
            'base_currency_code'    => 'EUR',
            'channel_currency_code' => 'EUR',
            'grand_total'           => 0.0000,
            'base_grand_total'      => 0.0000,
            'sub_total'             => 0.0000,
            'base_sub_total'        => 0.0000,
            'channel_id'            => 1,
            'created_at'            => $now,
            'updated_at'            => $now,
        ];
    }

    /**
     * Adjust customer.
     *
     * @return array
     */
    public function adjustCustomer()
    {
        return $this->state(function (array $attributes) {
            $customer = isset($attributes['customer_id'])
                ? Customer::query()->where('id', $attributes['customer_id'])->first()
                : Customer::factory()->create();

            return [
                'customer_id'         => $customer->id,
                'customer_email'      => $customer->email,
                'customer_first_name' => $customer->first_name,
                'customer_last_name'  => $customer->last_name,
            ];
        });
    }
}
