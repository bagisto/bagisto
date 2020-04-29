<?php

use Faker\Generator as Faker;
use Webkul\Category\Models\Category;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\Locale;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Channel::class, function (Faker $faker, array $attributes) {

    $seoTitle = $attributes['seo_title'] ?? $faker->word;
    $seoDescription = $attributes['seo_description'] ?? $faker->words(10, true);
    $seoKeywords = $attributes['seo_keywords'] ?? $faker->words(3, true);

    $seoData = [
        'meta_title'       => $seoTitle,
        'meta_description' => $seoDescription,
        'meta_keywords'    => $seoKeywords,
    ];

    unset($attributes['seo_title'], $attributes['seo_description'], $attributes['seo_keywords']);


    return [
        'code'              => $faker->unique()->word,
        'name'              => $faker->word,
        'default_locale_id' => function () {
            return factory(Locale::class)->create()->id;
        },
        'base_currency_id'  => function () {
            return factory(Currency::class)->create()->id;
        },
        'root_category_id'  => function () {
            return factory(Category::class)->create()->id;
        },
        'home_seo'          => json_encode($seoData),
    ];
});
