<?php

namespace Webkul\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Core\Repositories\CountryStateRepository;
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

        $country = $this->faker->countryCode;

        return [
            'company_name' => $this->faker->company,
            'vat_id' => $fakerIt->vat(),
            'email' => $this->faker->safeEmail(),
            'first_name' => preg_replace('/[^a-zA-Z ]/', '', $this->faker->firstName()),
            'last_name' => preg_replace('/[^a-zA-Z ]/', '', $this->faker->lastName()),
            'address' => $this->faker->streetAddress,
            'country' => $country,
            'state' => $this->stateOf($country),
            'city' => $this->faker->city,
            'postcode' => rand(11111, 99999),
            'phone' => $this->faker->e164PhoneNumber,
            'default_address' => $this->faker->boolean,
            'address_type' => CustomerAddress::ADDRESS_TYPE,
        ];
    }

    /**
     * A state code from the country's state list, or a free-form state when it has none.
     */
    protected function stateOf(string $country): string
    {
        $states = app(CountryStateRepository::class)->findByField('country_code', $country);

        return $states->isNotEmpty()
            ? $states->random()->code
            : $this->faker->state;
    }
}
