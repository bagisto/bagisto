<?php

use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should fail the validation with errors when certain inputs are not provided when store in simple product', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'))
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('attribute_family_id')
        ->assertJsonValidationErrorFor('sku')
        ->assertUnprocessable();
});

it('should return the create page of simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'simple',
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->uuid(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertModelWise([
        Product::class => [
            [
                'id'   => $productId,
                'type' => 'simple',
                'sku'  => $sku,
            ],
        ],
    ]);
});

it('should return the edit page of simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    $this->get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name)
        ->assertSeeText($product->short_description)
        ->assertSeeText($product->description);
});

it('should fail the validation with errors when certain inputs are not provided when update in simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id))
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('price')
        ->assertJsonValidationErrorFor('weight')
        ->assertUnprocessable();
});

it('should fail the validation with errors if certain data is not provided correctly in simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'visible_individually' => $unProcessAble = fake()->word(),
        'status'               => $unProcessAble,
        'guest_checkout'       => $unProcessAble,
        'new'                  => $unProcessAble,
        'featured'             => $unProcessAble,
    ])
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('price')
        ->assertJsonValidationErrorFor('weight')
        ->assertJsonValidationErrorFor('visible_individually')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('guest_checkout')
        ->assertJsonValidationErrorFor('new')
        ->assertJsonValidationErrorFor('featured')
        ->assertUnprocessable();
});

it('should update the simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'short_description' => $shortDescription = fake()->sentence(),
        'description'       => $description = fake()->paragraph(),
        'name'              => $name = fake()->words(3, true),
        'price'             => $price = fake()->randomFloat(2, 1, 1000),
        'weight'            => $weight = fake()->numberBetween(0, 100),
        'channel'           => $channel = core()->getCurrentChannelCode(),
        'locale'            => $locale = app()->getLocale(),
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertModelWise([
        Product::class => [
            [
                'id'   => $product->id,
                'type' => $product->type,
                'sku'  => $product->sku,
            ],
        ],

        ProductFlat::class => [
            [
                'product_id'        => $product->id,
                'url_key'           => $product->url_key,
                'sku'               => $product->sku,
                'type'              => 'simple',
                'name'              => $name,
                'short_description' => $shortDescription,
                'description'       => $description,
                'price'             => $price,
                'weight'            => $weight,
                'locale'            => $locale,
                'channel'           => $channel,
            ],
        ],
    ]);
});

it('should delete a simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});
