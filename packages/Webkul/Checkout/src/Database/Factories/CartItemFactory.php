<?php

namespace Webkul\Checkout\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Checkout\Models\CartItem;

class CartItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity'   => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Adjust product.
     */
    public function adjustProduct(): CartItemFactory
    {
        return $this->state(function () {
            $fallbackPrice = $this->faker->randomFloat(4, 0, 1000);

            return [
                'base_price' => $fallbackPrice,
                'price'      => $fallbackPrice,
                'total'      => $fallbackPrice,
                'base_total' => $fallbackPrice,
            ];
        });
    }
}
