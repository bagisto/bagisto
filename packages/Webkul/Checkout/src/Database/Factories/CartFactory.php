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
        $cartData = [
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
        ];

        if ($customer = auth()->guard()->user()) {
            $cartData = array_merge($cartData, [
                'customer_id'         => $customer->id,
                'is_guest'            => 0,
                'customer_first_name' => $customer->first_name,
                'customer_last_name'  => $customer->last_name,
                'customer_email'      => $customer->email,
            ]);
        } else {
            $cartData['is_guest'] = 1;
        }

        return $cartData;
    }
}
