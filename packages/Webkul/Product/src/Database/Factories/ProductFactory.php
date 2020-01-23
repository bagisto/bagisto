<?php

use Faker\Generator as Faker;
use Webkul\Product\Models\Product;
use Webkul\Attribute\Models\AttributeFamily;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Product::class, function (Faker $faker, array $attributes) {

    $attributeFamilyId = AttributeFamily::pluck('id')->first();

    return [
        'sku'        => $faker->uuid,
        'type' => 'simple',
        'attribute_family_id' => $attributeFamilyId,
    ];
});