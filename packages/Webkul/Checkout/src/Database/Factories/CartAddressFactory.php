<?php

namespace Webkul\Checkout\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Core\Repositories\CountryStateRepository;

class CartAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var CartAddress
     */
    protected $model = CartAddress::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $country = $this->faker->countryCode();

        return [
            'address' => implode(PHP_EOL, [$this->faker->address()]),
            'company_name' => $this->faker->company(),
            'first_name' => preg_replace('/[^a-zA-Z ]/', '', $this->faker->firstName()),
            'last_name' => preg_replace('/[^a-zA-Z ]/', '', $this->faker->lastName()),
            'email' => $this->faker->safeEmail(),
            'country' => $country,
            'state' => $this->stateOf($country),
            'city' => $this->faker->city(),
            'postcode' => $this->faker->numerify('######'),
            'phone' => $this->faker->e164PhoneNumber(),
            'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
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
            : $this->faker->randomElement(['Delhi', 'Mumbai', 'Kolkata', 'Rajasthan']);
    }
}
