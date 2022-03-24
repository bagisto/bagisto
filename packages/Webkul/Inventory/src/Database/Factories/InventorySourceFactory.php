<?php

namespace Webkul\Inventory\Database\Factories;

use Webkul\Inventory\Models\InventorySource;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     *
     * @return array
     */
    public function definition(): array
    {
        $now = date("Y-m-d H:i:s");
        $code = $this->faker->unique()->word;
        return [
            'code' => $this->faker->unique()->word,
            'name' => $code,
            'description' => $this->faker->sentence,
            'contact_name' => $this->faker->name,
            'contact_email' => $this->faker->safeEmail,
            'contact_number' => $this->faker->phoneNumber,
            'country' => $this->faker->countryCode,
            'state' => $this->faker->state,
            'city' => $this->faker->city,
            'street' => $this->faker->streetAddress,
            'postcode' => $this->faker->postcode,
            'priority' => 0,
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
