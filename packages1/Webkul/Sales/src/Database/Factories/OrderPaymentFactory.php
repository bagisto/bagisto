<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\OrderPayment;

$factory->define(OrderPayment::class, function (Faker $faker, array $attributes) {

    if (!array_key_exists('order_id', $attributes)) {
        throw new InvalidArgumentException('order_id must be provided.');
    }

    return [
        'method' => 'cashondelivery',
    ];
});

