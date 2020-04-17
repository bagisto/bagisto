<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Checkout\Models\CartAddress;

$factory->define(CartAddress::class, function (Faker $faker) {
    return [
        'first_name'   => $faker->firstName(),
        'last_name'    => $faker->lastName,
        'email'        => $faker->email,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ];
});
