<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Product\Models\ProductDownloadableSample;
use Webkul\Product\Models\ProductDownloadableSampleTranslation;

$factory->define(ProductDownloadableSample::class, function (Faker $faker) {
    $now = date("Y-m-d H:i:s");
    $filename = 'ProductImageExampleForUpload.jpg';
    $filepath = '/tests/_data/';
    return [
        'url'        => '',
        'file'       => $filepath . $filename,
        'file_name'  => $filename,
        'type'       => 'file',
        'created_at' => $now,
        'updated_at' => $now,
    ];
});

$factory->define(ProductDownloadableSampleTranslation::class, function (Faker $faker) {
    return [
        'locale' => 'en',
        'title'  => $faker->word,
    ];
});

