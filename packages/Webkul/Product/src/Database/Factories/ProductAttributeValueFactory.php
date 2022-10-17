<?php

namespace Webkul\Product\Database\Factories;

use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductAttributeValue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'locale'  => 'en',
            'channel' => 'default',
        ];
    }
}