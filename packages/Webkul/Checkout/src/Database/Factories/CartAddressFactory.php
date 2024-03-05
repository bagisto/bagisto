<?php

namespace Webkul\Checkout\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Checkout\Models\CartAddress;

class CartAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var \Webkul\Checkout\Models\CartAddress
     */
    protected $model = CartAddress::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name'   => $this->faker->firstName(),
            'last_name'    => $this->faker->lastName(),
            'phone'        => $this->faker->numerify('98########'),
            'address1'     => $this->faker->streetAddress(),
            'country'      => $this->faker->randomElement(['IN']),
            'state'        => $this->faker->randomElement(['Delhi', 'Mumbai', 'Kolkata', 'Rajasthan']),
            'city'         => $this->faker->city(),
            'postcode'     => $this->faker->numerify('######'),
            'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        ];
    }
}
