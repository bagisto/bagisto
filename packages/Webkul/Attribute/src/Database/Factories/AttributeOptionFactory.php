<?php

namespace Webkul\Attribute\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Attribute\Models\AttributeOption;

class AttributeOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttributeOption::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'admin_name'   => $this->faker->word,
            'sort_order'   => $this->faker->randomDigit(),
            'swatch_value' => null,
        ];
    }
}
