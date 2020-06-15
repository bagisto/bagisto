<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\BookingProduct\Models\BookingProductEventTicket;

$factory->define(BookingProductEventTicket::class, function (Faker $faker, array $attributes) {
    return [
        'price' => $faker->randomFloat(4, 3, 900),
        'qty'   => $faker->randomNumber(2),

        'booking_product_id' => static function () {
            return factory(BookingProduct::class)->create(['type' => 'event'])->id;
        },
    ];
});