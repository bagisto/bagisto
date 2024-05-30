<?php

namespace Webkul\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Inventory\Models\InventorySource;

class InventorySourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventorySource::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code'           => $this->faker->unique()->word,
            'name'           => $this->faker->unique()->word,
            'description'    => $this->faker->sentence,
            'contact_name'   => preg_replace('/[^a-zA-Z ]/', '', $this->faker->name()),
            'contact_email'  => $this->faker->safeEmail,
            'contact_number' => $this->faker->phoneNumber,
            'country'        => $this->faker->countryCode,
            'state'          => $this->faker->state,
            'city'           => $this->faker->city,
            'street'         => $this->faker->streetAddress,
            'postcode'       => $this->faker->postcode,
            'priority'       => 0,
            'status'         => 1,
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }
}
