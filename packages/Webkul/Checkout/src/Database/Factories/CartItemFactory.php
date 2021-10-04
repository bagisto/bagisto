<?php

namespace Webkul\Checkout\Database\Factories;

use Faker\Generator as Faker;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $now = date("Y-m-d H:i:s");

        if (isset($attributes['product_id'])) {
            $product = Product::query()
                              ->where('id', $attributes['product_id'])
                              ->first();
        } else {
            $product = Product::factory()
                              ->create();
        }

        $fallbackPrice = $this->faker->randomFloat(4, 0, 1000);

        return [
            'quantity'   => 1,
            'sku'        => $product->sku,
            'type'       => $product->type,
            'name'       => $product->name,
            'price'      => $product->price ?? $fallbackPrice,
            'base_price' => $product->price ?? $fallbackPrice,
            'total'      => $product->price ?? $fallbackPrice,
            'base_total' => $product->price ?? $fallbackPrice,
            'product_id' => $product->id,
            'cart_id'    => Cart::factory(),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

}
