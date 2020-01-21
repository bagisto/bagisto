<?php

use Faker\Generator as Faker;
use Webkul\Category\Models\Category;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Category::class, function (Faker $faker, array $attributes) {

    return [
        'status'    => 1,
        'position'  => $faker->randomDigit,
        'parent_id' => 1,
    ];
});

$factory->state(Category::class, 'inactive', [
    'status' => 0,
]);
