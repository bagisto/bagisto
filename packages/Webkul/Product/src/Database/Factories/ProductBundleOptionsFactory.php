<?php

namespace Webkul\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Models\ProductBundleOption;

class ProductBundleOptionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductBundleOption::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type'        => $this->faker->randomElement(['select', 'radio', 'checkbox', 'multiselect']),
            'is_required' => 0,
            'sort_order'  => 0,
        ];
    }
}
