<?php

namespace Webkul\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Sales\Models\OrderAddress;

class OrderAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderAddress::class;

    /**
     * @var string[]
     */
    protected $states = [
        'shipping',
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->countryCode(),
            'postcode' => $this->faker->postcode(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }

    public function shipping(): void
    {
        $this->state(function () {
            return [
                'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
            ];
        });
    }
}
