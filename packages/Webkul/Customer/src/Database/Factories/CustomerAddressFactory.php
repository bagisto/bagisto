<?php

namespace Webkul\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Customer\Models\CustomerAddress;

class CustomerAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerAddress::class;

    /**
     * Define the model's default state.
     *
     * @throws \Exception
     */
    public function definition(): array
    {
        $fakerIt = \Faker\Factory::create('it_IT');

        return [
            'company_name'    => $this->faker->company,
            'vat_id'          => $fakerIt->vatId(),
            'email'           => $this->faker->email,
            'first_name'      => $this->faker->firstName,
            'last_name'       => $this->faker->lastName,
            'address'         => $this->faker->streetAddress,
            'country'         => $this->faker->countryCode,
            'state'           => $this->faker->state,
            'city'            => $this->faker->city,
            'postcode'        => rand(11111, 99999),
            'phone'           => $this->faker->e164PhoneNumber,
            'default_address' => $this->faker->boolean,
            'address_type'    => CustomerAddress::ADDRESS_TYPE,
        ];
    }
}
