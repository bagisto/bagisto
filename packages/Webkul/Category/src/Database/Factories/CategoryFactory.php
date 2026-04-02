<?php

namespace Webkul\Category\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Category\Models\Category;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'status' => 1,
            'position' => $this->faker->randomDigit(),
            'parent_id' => 1,
        ];
    }

    /**
     * Mark the category as inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 0]);
    }
}
