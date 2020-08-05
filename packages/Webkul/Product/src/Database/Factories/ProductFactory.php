<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Product\Models\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'sku'                 => $faker->uuid,
        'attribute_family_id' => 1,
    ];
});

$factory->state(Product::class, 'simple', [
    'type' => 'simple',
]);

$factory->state(Product::class, 'virtual', [
    'type' => 'virtual',
]);

$factory->state(Product::class, 'downloadable', [
    'type' => 'downloadable',
]);

$factory->state(Product::class, 'booking', [
    'type' => 'booking',
]);