<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Checkout\Models\CartAddress;

$factory->define(CartAddress::class, function (Faker $faker) {
    $now = date("Y-m-d H:i:s");
    return [
        'first_name'   => $faker->firstName(),
        'last_name'    => $faker->lastName,
        'email'        => $faker->email,
        'created_at'   => $now,
        'updated_at'   => $now,
        'address_type' => 'billing',
    ];
});
