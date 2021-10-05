<?php

namespace Webkul\Product\Database\Factories;

use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductReview;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title'      => $this->faker->words(5, true),
            'rating'     => $this->faker->numberBetween(0, 10),
            'status'     => 1,
            'comment'    => $this->faker->sentence(20),
            'product_id' => Product::factory(),
        ];
    }
}
