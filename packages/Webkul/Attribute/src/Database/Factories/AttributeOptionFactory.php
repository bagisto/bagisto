<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Core\Models\Locale;

$factory->define(AttributeOption::class, function (Faker $faker, array $attributes) {

    $locales = Locale::pluck('code')->all();

    // array $attributes does not contain any locale code
    if (count(array_diff_key(array_flip($locales), $attributes) ) === count($locales)) {
        $localeCode = $locales[0];

        $attributes[$localeCode] = [
            'label' => $faker->word,
        ];
    }

    return [
        'admin_name'   => $faker->word,
        'sort_order'   => $faker->randomDigit,
        'attribute_id' => function () {
            return factory(Attribute::class)->create()->id;
        },
        'swatch_value' => null,
    ];
});

$factory->define(AttributeOption::class, function (Faker $faker, array $attributes) {
    return [
        'admin_name'   => $faker->word,
        'sort_order'   => $faker->randomDigit,
        'attribute_id' => function () {
            return factory(Attribute::class)
                ->create(['swatch_type' => 'color'])
                ->id;
        },
        'swatch_value' => $faker->hexColor,
    ];
});

$factory->define(AttributeOption::class, function (Faker $faker, array $attributes) {
    return [
        'admin_name'   => $faker->word,
        'sort_order'   => $faker->randomDigit,
        'attribute_id' => function () {
            return factory(Attribute::class)
                ->create(['swatch_type' => 'image'])
                ->id;
        },
        'swatch_value' => '/tests/_data/ProductImageExampleForUpload.jpg',
    ];
});

$factory->define(AttributeOption::class, function (Faker $faker, array $attributes) {
    return [
        'admin_name'   => $faker->word,
        'sort_order'   => $faker->randomDigit,
        'attribute_id' => function () {
            return factory(Attribute::class)
                ->create(['swatch_type' => 'dropdown'])
                ->id;
        },
        'swatch_value' => null,
    ];
});

$factory->define(AttributeOption::class, function (Faker $faker, array $attributes) {
    return [
        'admin_name'   => $faker->word,
        'sort_order'   => $faker->randomDigit,
        'attribute_id' => function () {
            return factory(Attribute::class)
                ->create(['swatch_type' => 'text'])
                ->id;
        },
        'swatch_value' => null,
    ];
});