<?php

namespace Webkul\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductOrderedInventory;

class ProductOrderedInventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductOrderedInventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'qty'        => $this->faker->numberBetween(100, 200),
            'product_id' => Product::factory(),
            'channel_id' => 1,
        ];
    }
}
