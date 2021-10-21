<?php

namespace Webkul\Attribute\Database\Factories;

use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'admin_name'   => $this->faker->word,
            'sort_order'   => $this->faker->randomDigit(),
            'attribute_id' => Attribute::factory(['swatch_type' => 'text']),
            'swatch_value' => null,
        ];
    }
}