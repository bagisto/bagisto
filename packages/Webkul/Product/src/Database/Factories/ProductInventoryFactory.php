<?php

namespace Webkul\Product\Database\Factories;

use Webkul\Inventory\Models\InventorySource;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductInventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductInventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'qty'                 => $this->faker->numberBetween(100, 200),
            'product_id'          => Product::factory(),
            'inventory_source_id' => InventorySource::factory(),
        ];
    }
}