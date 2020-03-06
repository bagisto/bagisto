<?php

use Faker\Generator as Faker;
use Webkul\Core\Models\Locale;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Locale::class, function (Faker $faker, array $attributes) {
    return [
        'code'      => $faker->languageCode,
        'name'      => $faker->country,
        'direction' => 'ltr',
    ];
});

$factory->state(Category::class, 'rtl', [
    'direction' => 'rtl',
]);
