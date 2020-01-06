<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Attribute\Models\Attribute;
use Webkul\Core\Models\Locale;

$factory->define(Attribute::class, function (Faker $faker, array $attributes) {
    $types = [
        'text',
        'textarea',
        'price',
        'boolean',
        'select',
        'multiselect',
        'datetime',
        'date',
        'image',
        'file',
        'checkbox',
    ];

    $locales = Locale::pluck('code')->all();

    // array $attributes does not contain any locale code
    if (count(array_diff_key(array_flip($locales), $attributes) ) === count($locales)) {
        $localeCode = $locales[0];

        $attributes[$localeCode] = [
            'name' => $faker->word,
        ];
    }

    return [
        'admin_name' => $faker->word,
        'code' => $faker->word,
        'type' => array_rand($types),
        'validation' => '',
        'position' => $faker->randomDigit,
        'is_required' => false,
        'is_unique' => false,
        'value_per_locale' => false,
        'value_per_channel' => false,
        'is_filterable' => false,
        'is_configurable' => false,
        'is_user_defined' => true,
        'is_visible_on_front' => true,
        'swatch_type' => null,
        'use_in_flat' => true,
    ];
});

$factory->state(Attribute::class, 'validation_numeric', [
    'validation' => 'numeric',
]);

$factory->state(Attribute::class, 'validation_email', [
    'validation' => 'email',
]);

$factory->state(Attribute::class, 'validation_decimal', [
    'validation' => 'decimal',
]);

$factory->state(Attribute::class, 'validation_url', [
    'validation' => 'url',
]);

$factory->state(Attribute::class, 'required', [
    'is_required' => true,
]);

$factory->state(Attribute::class, 'unique', [
    'is_unique' => true,
]);

$factory->state(Attribute::class, 'filterable', [
    'is_filterable' => true,
]);

$factory->state(Attribute::class, 'configurable', [
    'is_configurable' => true,
]);