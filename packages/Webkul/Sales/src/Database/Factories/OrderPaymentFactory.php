<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\OrderPayment;

$factory->define(OrderPayment::class, function (Faker $faker) {
    $now = date("Y-m-d H:i:s");

    return [
        'created_at' => $now,
        'updated_at' => $now,
    ];
});

