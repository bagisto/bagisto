<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Sales\Models\Order;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\OrderItem;

$factory->define(OrderItem::class, function (Faker $faker, array $attributes) {
    $now = date("Y-m-d H:i:s");

    if (isset($attributes['product_id'])) {
        $product = Product::where('id', $attributes['product_id'])->first();
    } else {
        $product = factory(Product::class)->create();
    }

    $fallbackPrice = $faker->randomFloat(4, 0, 1000);

    return [
        'sku'          => $product->sku,
        'type'         => $product->type,
        'name'         => $product->name,
        'price'        => $product->price ?? $fallbackPrice,
        'base_price'   => $product->price ?? $fallbackPrice,
        'total'        => $product->price ?? $fallbackPrice,
        'base_total'   => $product->price ?? $fallbackPrice,
        'product_id'   => $product->id,
        'qty_ordered'  => 1,
        'qty_shipped'  => 0,
        'qty_invoiced' => 0,
        'qty_canceled' => 0,
        'qty_refunded' => 0,
        'additional'   => [],
        'order_id'     => function () {
            return factory(Order::class)->create()->id;
        },
        'created_at'   => $now,
        'updated_at'   => $now,
        'product_type' => Product::class,
    ];
});