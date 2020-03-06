<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Attribute\Models\AttributeOption;

$factory->defineAs(ProductAttributeValue::class, 'sku', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'text_value'   => $faker->uuid,
        'attribute_id' => 1,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'name', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'locale'       => 'en', //$faker->languageCode,
        'channel'      => 'default',
        'text_value'   => $faker->words(2, true),
        'attribute_id' => 2,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'url_key', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'text_value'   => $faker->unique()->slug,
        'attribute_id' => 3,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'tax_category_id', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'channel'       => 'default',
        'integer_value' => null,
        'attribute_id'  => 4,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'new', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'boolean_value' => 1,
        'attribute_id'  => 5,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'featured', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'boolean_value' => 1,
        'attribute_id'  => 6,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'visible_individually', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'boolean_value' => 1,
        'attribute_id'  => 7,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'status', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'boolean_value' => 1,
        'attribute_id'  => 8,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'short_description', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'locale'       => 'en', //$faker->languageCode,
        'channel'      => 'default',
        'text_value'   => $faker->sentence,
        'attribute_id' => 9,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'description', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'locale'       => 'en', //$faker->languageCode,
        'channel'      => 'default',
        'text_value'   => $faker->sentences(3, true),
        'attribute_id' => 10,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'price', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'float_value'  => $faker->randomFloat(4, 0, 1000),
        'attribute_id' => 11,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'cost', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'channel'      => 'default',
        'float_value'  => $faker->randomFloat(4, 0, 10),
        'attribute_id' => 12,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'special_price', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'float_value'  => $faker->randomFloat(4, 0, 100),
        'attribute_id' => 13,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'special_price_from', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'channel'      => 'default',
        'date_value'   => $faker->dateTimeBetween('-5 days', 'now', 'Europe/Berlin'),
        'attribute_id' => 14,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'special_price_to', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'channel'      => 'default',
        'date_value'   => $faker->dateTimeBetween('now', '+ 5 days', 'Europe/Berlin'),
        'attribute_id' => 15,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'meta_title', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'locale'       => 'en', //$faker->languageCode,
        'channel'      => 'default',
        'text_value'   => $faker->words(2, true),
        'attribute_id' => 16,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'meta_keywords', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'locale'       => 'en', //$faker->languageCode,
        'channel'      => 'default',
        'text_value'   => $faker->words(5, true),
        'attribute_id' => 17,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'meta_description', function (Faker $faker) {
    return [
        'product_id'   => function () {
            return factory(Product::class)->create()->id;
        },
        'locale'       => 'en', //$faker->languageCode,
        'channel'      => 'default',
        'text_value'   => $faker->sentence,
        'attribute_id' => 18,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'width', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'integer_value' => $faker->numberBetween(1, 50),
        'attribute_id'  => 19,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'height', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'integer_value' => $faker->numberBetween(1, 50),
        'attribute_id'  => 20,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'depth', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'integer_value' => $faker->numberBetween(1, 50),
        'attribute_id'  => 21,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'weight', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'integer_value' => $faker->numberBetween(1, 50),
        'attribute_id'  => 22,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'color', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'integer_value' => $faker->numberBetween(1, 5),
        'attribute_id'  => 23,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'size', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'integer_value' => $faker->numberBetween(1, 5),
        'attribute_id'  => 24,
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'brand', function (Faker $faker) {
    return [
        'product_id'    => function () {
            return factory(Product::class)->create()->id;
        },
        'attribute_id'  => 25,
        'integer_value' => function () {
            return factory(AttributeOption::class)->create()->id;
        },
    ];
});

$factory->defineAs(ProductAttributeValue::class, 'guest_checkout', function ( Faker $faker) {
    return [
        'product_id' => function() {
            return factory(Product::class)->create()->id;
        },
        'boolean_value' => 1,
        'attribute_id'  => 26,
    ];
});