<?php

use Faker\Generator as Faker;
use Webkul\Core\Models\Locale;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Locale::class, function (Faker $faker, array $attributes) {

    $languageCode = $faker->languageCode;

    $locale = Locale::query()->firstWhere('code', $languageCode);
    if ($locale !== null) {
        return $locale->id;
    }

    return [
        'code'      => $languageCode,
        'name'      => $faker->country,
        'direction' => 'ltr',
    ];
});

$factory->state(Category::class, 'rtl', [
    'direction' => 'rtl',
]);
