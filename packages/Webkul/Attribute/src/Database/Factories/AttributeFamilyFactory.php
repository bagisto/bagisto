<?php

namespace Webkul\Attribute\Database\Factories;

use Webkul\Attribute\Models\AttributeFamily;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFamilyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttributeFamily::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'name'            => $this->faker->word(),
            'code'            => $this->faker->word(),
            'is_user_defined' => random_int(0, 1),
            'status'          => 0,
        ];
    }
}