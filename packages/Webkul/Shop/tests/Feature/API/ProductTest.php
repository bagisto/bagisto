<?php

use Pest\Expectation;
use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\getJson;

it('returns a new products listing', function () {
    // Arrange
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

    (new ProductFaker($newProductOptions))
        ->getSimpleProductFactory()
        ->create();

    // Act
    $response = getJson(route('shop.api.products.index', ['new' => 1]))
        ->assertOk()
        ->collect();

    // Assert
    expect($response['data'])->each(function (Expectation $product) {
        return $product->is_new->toBeTrue();
    });
});

it('returns a featured products listing', function () {
    // Arrange
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

    (new ProductFaker($featuredProductOptions))
        ->getSimpleProductFactory()
        ->create();

    // Act
    $response = getJson(route('shop.api.products.index', ['featured' => 1]))
        ->assertOk()
        ->collect();

    // Assert
    expect($response['data'])->each(function (Expectation $product) {
        return $product->is_featured->toBeTrue();
    });
});

it('returns all products listing', function () {
    // Arrange
    $product = (new ProductFaker())
        ->getSimpleProductFactory()
        ->create();

    // Act & Assert
    getJson(route('shop.api.products.index'))
        ->assertOk()
        ->assertJsonIsArray('data')
        ->assertJsonFragment([
            'id' => $product->id,
        ]);
});
