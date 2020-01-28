<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Product\Models\ProductReview;

$factory->define(ProductReview::class, function (Faker $faker, array $attributes) {
    if (!array_key_exists('product_id', $attributes)) {
        throw new InvalidArgumentException('product_id must be provided. You may use $I->haveProduct() to generate a product');
    }

    return [
        'title'      => $faker->words(5, true),
        'rating'     => $faker->numberBetween(0, 10),
        'status'     => 1,
        'comment'    => $faker->sentence(20),
        'product_id' => $attributes['product_id'],
    ];
});
