<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\OrderItem;

$factory->define(InvoiceItem::class, function (Faker $faker, array $attributes) {

    $basePrice = $faker->randomFloat(2);
    $quantity = $faker->randomNumber();

    if (! $attributes['order_item_id']) {
        $attributes['order_item_id'] = function () {
            return factory(OrderItem::class)->create()->id;
        };
    }

    $orderItem = OrderItem::find($attributes['order_item_id']);

    return [
        'name'            => $faker->word,
        'sku'             => $faker->unique()->ean13,
        'qty'             => $quantity,
        'price'           => $basePrice,
        'base_price'      => $basePrice,
        'total'           => $quantity * $basePrice,
        'base_total'      => $quantity * $basePrice,
        'tax_amount'      => 0,
        'base_tax_amount' => 0,
        'product_id'      => $orderItem->product_id,
        'product_type'    => $orderItem->product_type,
        'order_item_id'   => $attributes['order_item_id'],
        'invoice_id'      => function () {
            return factory(Invoice::class)->create()->id;
        },
    ];
});
