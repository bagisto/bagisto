<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\Product\Models\Product;

$factory->define(BookingProduct::class, function (Faker $faker, array $attributes) {
    $bookingTypes = ['event'];

    return [
        'type' => $bookingTypes[array_rand(['event'])],
        'qty' => $faker->randomNumber(2),
        'available_from' => Carbon::yesterday(),
        'available_to' => Carbon::tomorrow(),
        'product_id' => function () {
            return factory(Product::class)->create(['type' => 'booking'])->id;
        }
    ];
});