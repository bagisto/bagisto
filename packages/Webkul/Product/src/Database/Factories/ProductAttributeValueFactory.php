<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;

$factory->define(ProductAttributeValue::class, function (Faker $faker) {
    return [
        'product_id' => function () {
            return factory(Product::class)->create()->id;
        },
        'locale'     => 'en',
        'channel'    => 'default',
    ];
});
