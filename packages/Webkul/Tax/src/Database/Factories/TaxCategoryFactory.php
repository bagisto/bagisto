<?php

namespace Webkul\Tax\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Tax\Models\TaxCategory;

class TaxCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaxCategory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code'        => $this->faker->uuid,
            'name'        => $this->faker->words(2, true),
            'description' => $this->faker->sentence(10),
        ];
    }
}
