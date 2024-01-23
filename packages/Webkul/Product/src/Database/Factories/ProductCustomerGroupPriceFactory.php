<?php

namespace Webkul\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Models\ProductCustomerGroupPrice;

class ProductCustomerGroupPriceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductCustomerGroupPrice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
