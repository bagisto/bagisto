<?php

use Faker\Generator as Faker;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Currency;
use Webkul\Inventory\Models\InventorySource;
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

    /*if (! isset($attributes['locales'])) {
        $attributes['default_locale_id'] = $attributes['default_locale_id'] ?? function () {
            return factory(Locale::class)->create()->id;
        };
    }

    unset($attributes['locales']);

    if (! isset($attributes['inventory_sources'])) {
        $attributes['inventory_sources'] = $attributes['inventory_sources'] ?? function () {
            return factory(InventorySource::class)->create()->id;
        };
    }

    unset($attributes['inventory_sources']);

    if (! isset($attributes['currencies'])) {
        $attributes['base_currency_id'] = $attributes['base_currency_id'] ?? function () {
            return factory(Currency::class)->create()->id;
        };
    }

    unset($attributes['currencies']);*/

    return [
        'code'              => $faker->unique()->word,
        'name'              => $faker->word,
        'default_locale_id' => function () {
            return factory(Locale::class)->create()->id;
        },
        'base_currency_id'  => function () {
            return factory(Currency::class)->create()->id;
        },
        'root_category_id'  => function() {
            return factory(\Webkul\Category\Models\Category::class)->create()->id;
        },
        'home_seo'          => json_encode($seoData),
    ];
});

/*$factory->afterCreating(Channel::class, function (Channel $channel, Faker $faker) {
    $channel->locales()->sync($data['locales']);
}, 'channel_sync_locales');

$factory->afterCreating(Channel::class, function (Channel $channel, Faker $faker) {
    $channel->currencies()->sync($data['currencies']);
});

$factory->afterCreating(Channel::class, function (Channel $channel, Faker $faker) {
    $channel->inventory_sources()->sync($data['inventory_sources']);
});*/