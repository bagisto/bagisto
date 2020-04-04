<?php

use Faker\Generator as Faker;
use Webkul\Core\Models\Locale;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Locale::class, function (Faker $faker, array $attributes) {

    do {
        $languageCode = $faker->languageCode;
    } while (Locale::query()->where('code', $languageCode)->exists());

    return [
        'code'      => $languageCode,
        'name'      => $faker->country,
        'direction' => 'ltr',
    ];
});

$factory->state(Category::class, 'rtl', [
    'direction' => 'rtl',
]);