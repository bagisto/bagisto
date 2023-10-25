<?php

use Pest\Expectation;
use Webkul\Faker\Helpers\Product;
use Webkul\Product\Models\Product as ProductModel;

use function Pest\Laravel\getJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    ProductModel::query()->delete();
});

it('returns a new products listing', function () {
    // Prepare
    $newProductOptions = [
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ];

    (new Product($newProductOptions))->create(1, 'simple');

    // Act
    $response = getJson(route('shop.api.products.index', ['new' => 1]))->collect();

    // Assert
    expect($response['data'])->each(function (Expectation $product) {
        return $product->is_new->toBeTrue();
    });
});

it('returns a featured products listing', function () {
    // Prepare
    $featuredProductOptions = [
        'attributes' => [
            6 => 'featured',
        ],

        'attribute_value' => [
            'featured' => [
                'boolean_value' => true,
            ],
        ],
    ];

    (new Product($featuredProductOptions))->create(1, 'simple');

    // Act
    $response = getJson(route('shop.api.products.index', ['featured' => 1]))->collect();

    // Assert
    expect($response['data'])->each(function (Expectation $product) {
        return $product->is_featured->toBeTrue();
    });
});

it('returns all products listing', function () {
    // Prepare
    $product = (new Product())->create(1, 'simple')->first();

    // Act & Assert
    getJson(route('shop.api.products.index'))
        ->assertJsonIsArray('data')
        ->assertJsonFragment([
            'id' => $product->id,
        ]);
});
