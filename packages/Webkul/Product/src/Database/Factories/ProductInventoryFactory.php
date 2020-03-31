<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductInventory;

$factory->define(ProductInventory::class, function (Faker $faker) {
    return [
        'qty'                 => $faker->numberBetween(1, 80),
        'product_id'          => function () {
            return factory(Product::class)->create()->id;
        },
        'inventory_source_id' => function () {
            return factory(InventorySource::class)->create()->id;
        },
    ];
});