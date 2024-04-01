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
            'address'          => implode(PHP_EOL, [$this->faker->address()]),
            'company_name'     => $this->faker->company(),
            'first_name'       => $this->faker->firstName(),
            'last_name'        => $this->faker->lastName(),
            'email'            => $this->faker->email(),
            'country'          => $this->faker->countryCode(),
            'state'            => $this->faker->randomElement(['Delhi', 'Mumbai', 'Kolkata', 'Rajasthan']),
            'city'             => $this->faker->city(),
            'postcode'         => $this->faker->numerify('######'),
            'phone'            => $this->faker->e164PhoneNumber(),
            'address_type'     => CartAddress::ADDRESS_TYPE_BILLING,
        ];
    }
}
