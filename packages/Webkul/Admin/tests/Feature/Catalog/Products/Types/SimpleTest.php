<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product as ProductModel;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Clean up all the records.
     */
    ProductModel::query()->delete();
    Attribute::query()->whereNotBetween('id', [1, 28])->delete();
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

    $this->assertDatabaseHas('products', [
        'id'   => $productId,
        'type' => 'simple',
        'sku'  => $sku,
    ]);
});

it('should return the edit page of simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Asssert
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

it('should update the simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Asssert
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

    $this->assertDatabaseHas('products', [
        'id'   => $product->id,
        'type' => $product->type,
        'sku'  => $product->sku,
    ]);

    $this->assertDatabaseHas('product_flat', [
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
