<?php

namespace Webkul\Checkout\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Checkout\Models\CartShippingRate;

class CartShippingRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartShippingRate::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'is_calculate_tax'     => 1,
            'discount_amount'      => 0.0000,
            'base_discount_amount' => 0.0000,
            'created_at'           => now(),
            'updated_at'           => now(),
        ];
    }
}
