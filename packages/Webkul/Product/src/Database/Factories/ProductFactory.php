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
        'virtual',
        'downloadable',
        'booking',
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'sku'                 => $this->faker->uuid,
            'attribute_family_id' => 1,
        ];
    }

    public function simple(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'simple',
            ];
        });
    }

    public function virtual(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'virtual',
            ];
        });
    }

    public function downloadable(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'downloadable',
            ];
        });
    }

    public function booking(): ProductFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'booking',
            ];
        });
    }
}
