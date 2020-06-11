<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Product\Models\Product;

$factory->define(CartItem::class, function (Faker $faker, array $attributes) {
    $now = date("Y-m-d H:i:s");

    if (isset($attributes['product_id'])) {
        $product = Product::where('id', $attributes['product_id'])->first();
    } else {
        $product = factory(Product::class)->create();
    }

    $fallbackPrice = $faker->randomFloat(4, 0, 1000);

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
        'cart_id'    => function () {
            return factory(Cart::class)->create()->id;
        },
        'created_at' => $now,
        'updated_at' => $now,
    ];
});

