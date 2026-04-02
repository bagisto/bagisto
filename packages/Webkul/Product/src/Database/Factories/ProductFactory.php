<?php

namespace Webkul\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sku' => $this->faker->uuid(),
            'type' => 'simple',
            'attribute_family_id' => 1,
        ];
    }

    /**
     * Set the product type to simple.
     */
    public function simple(): static
    {
        return $this->state(fn () => ['type' => 'simple']);
    }

    /**
     * Set the product type to virtual.
     */
    public function virtual(): static
    {
        return $this->state(fn () => ['type' => 'virtual']);
    }

    /**
     * Set the product type to grouped.
     */
    public function grouped(): static
    {
        return $this->state(fn () => ['type' => 'grouped']);
    }

    /**
     * Set the product type to configurable.
     */
    public function configurable(): static
    {
        return $this->state(fn () => ['type' => 'configurable']);
    }

    /**
     * Set the product type to downloadable.
     */
    public function downloadable(): static
    {
        return $this->state(fn () => ['type' => 'downloadable']);
    }

    /**
     * Set the product type to bundle.
     */
    public function bundle(): static
    {
        return $this->state(fn () => ['type' => 'bundle']);
    }
}
