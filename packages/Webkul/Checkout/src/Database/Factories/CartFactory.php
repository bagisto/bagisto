<?php

namespace Webkul\Checkout\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Checkout\Models\Cart;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'is_guest'              => 0,
            'is_active'             => 1,
            'is_gift'               => 0,
            'base_currency_code'    => 'EUR',
            'channel_currency_code' => 'EUR',
            'grand_total'           => 0.0000,
            'base_grand_total'      => 0.0000,
            'sub_total'             => 0.0000,
            'base_sub_total'        => 0.0000,
            'channel_id'            => 1,
            'created_at'            => now(),
            'updated_at'            => now(),
        ];
    }
}
