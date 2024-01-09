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
     * States.
     *
     * @var string[]
     */
    protected $states = [
        'simple',
        'configurable',
        'virtual',
        'grouped',
        'downloadable',
        'bundle',
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sku'                 => $this->faker->uuid,
            'attribute_family_id' => 1,
        ];
    }

    /**
     * Simple state.
     */
    public function simple(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'simple',
            ];
        });
    }

    /**
     * Virtual state.
     */
    public function virtual(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'virtual',
            ];
        });
    }

    /**
     * Grouped state.
     */
    public function grouped(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'grouped',
            ];
        });
    }

    /**
     * Configurable state.
     */
    public function configurable(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'configurable',
            ];
        });
    }

    /**
     * Downloadable state.
     */
    public function downloadable(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'downloadable',
            ];
        });
    }

    /**
     * Bundle state.
     */
    public function bundle(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'bundle',
            ];
        });
    }
}
