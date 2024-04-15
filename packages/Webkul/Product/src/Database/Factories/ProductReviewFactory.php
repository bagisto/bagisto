<?php

namespace Webkul\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Models\ProductReview;

class ProductReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductReview::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title'   => $this->faker->words(5, true),
            'rating'  => $this->faker->numberBetween(0, 10),
            'status'  => 'pending',
            'comment' => $this->faker->sentence(20),
        ];
    }
}
