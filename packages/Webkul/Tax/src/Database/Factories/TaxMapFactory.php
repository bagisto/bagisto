<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;
use Webkul\Tax\Models\TaxCategory;

$factory->define(TaxMap::class, function (Faker $faker) {
    return [
        'tax_category_id' => function () {
            return factory(TaxCategory::class)->create()->id;
        },
        'tax_rate_id' => function () {
            return factory(TaxRate::class)->create()->id;
        },
    ];
});
