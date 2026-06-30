<?php

use Webkul\Customer\Models\Customer as ModelsCustomer;
use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('should add the product to the compare list and return the updated list', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    // Act and Assert.
    $this->loginAsCustomer();

    postJson(route('shop.api.compare.store'), [
        'product_id' => $product->id,
    ])
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $product->id)
        ->assertJsonPath('message', trans('shop::app.compare.item-add-success'));
});

it('should fail when adding the same product to the compare list twice', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $customer = ModelsCustomer::factory()->create();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.compare.store'), [
        'product_id' => $product->id,
    ])->assertOk();

    postJson(route('shop.api.compare.store'), [
        'product_id' => $product->id,
    ])
        ->assertUnprocessable()
        ->assertJsonPath('data.message', trans('shop::app.compare.already-added'));
});

it('should remove the product from the compare list and return the updated list', function () {
    // Arrange.
    $products = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->count(2)->create();

    $customer = ModelsCustomer::factory()->create();

    $this->loginAsCustomer($customer);

    foreach ($products as $product) {
        postJson(route('shop.api.compare.store'), [
            'product_id' => $product->id,
        ])->assertOk();
    }

    // Act and Assert.
    deleteJson(route('shop.api.compare.destroy'), [
        'product_id' => $products->first()->id,
    ])
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('message', trans('shop::app.compare.remove-success'));
});

it('should remove all products from the compare list', function () {
    // Arrange.
    $products = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getSimpleProductFactory()->count(2)->create();

    $customer = ModelsCustomer::factory()->create();

    $this->loginAsCustomer($customer);

    foreach ($products as $product) {
        postJson(route('shop.api.compare.store'), [
            'product_id' => $product->id,
        ])->assertOk();
    }

    // Act and Assert.
    deleteJson(route('shop.api.compare.destroy_all'))
        ->assertOk()
        ->assertJsonPath('data.message', trans('shop::app.compare.remove-all-success'));

    getJson(route('shop.api.compare.index'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
