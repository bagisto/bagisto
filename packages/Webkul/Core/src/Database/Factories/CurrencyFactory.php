<?php

use Faker\Generator as Faker;
use Webkul\Core\Models\Currency;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Currency::class, function (Faker $faker, array $attributes) {

    return [
        'code' => $faker->unique()->currencyCode,
        'name' => $faker->word,
    ];

});