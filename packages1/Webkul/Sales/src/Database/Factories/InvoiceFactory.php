<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;

$factory->define(Invoice::class, function (Faker $faker, array $attributes) {
    $subTotal = $faker->randomFloat(2);
    $shippingAmount = $faker->randomFloat(2);
    $taxAmount = $faker->randomFloat(2);

    if (! isset($attributes['order_id'])) {
        $attributes['order_id'] = function () {
            return factory(Order::class)->create()->id;
        };
    }

    if (! isset($attributes['order_address_id'])) {
        $attributes['order_address_id'] = function () use ($attributes) {
            return factory(OrderAddress::class)
                ->create(['order_id' => $attributes['order_id']])
                ->id;
        };
    }

    return [
        'email_sent'            => 0,
        'total_qty'             => $faker->randomNumber(),
        'base_currency_code'    => 'EUR',
        'channel_currency_code' => 'EUR',
        'order_currency_code'   => 'EUR',
        'sub_total'             => $subTotal,
        'base_sub_total'        => $subTotal,
        'grand_total'           => $subTotal,
        'base_grand_total'      => $subTotal,
        'shipping_amount'       => $shippingAmount,
        'base_shipping_amount'  => $shippingAmount,
        'tax_amount'            => $taxAmount,
        'base_tax_amount'       => $taxAmount,
        'discount_amount'       => 0,
        'base_discount_amount'  => 0,
        'order_id'              => $attributes['order_id'],
        'order_address_id'      => $attributes['order_address_id'],
    ];
});

$factory->state(Invoice::class, 'pending', [
    'status' => 'pending',
]);

$factory->state(Invoice::class, 'paid', [
    'status' => 'paid',
]);

$factory->state(Invoice::class, 'refunded', [
    'status' => 'refunded',
]);

