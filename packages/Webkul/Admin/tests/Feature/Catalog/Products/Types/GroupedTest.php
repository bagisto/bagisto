<?php

use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Models\ProductGroupedProduct;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should fail the validation with errors when certain inputs are not provided when store in grouped product', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'))
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('attribute_family_id')
        ->assertJsonValidationErrorFor('sku')
        ->assertUnprocessable();
});

it('should return the create page of grouped product', function () {
    // Arrange.
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'grouped',
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->slug(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertModelWise([
        Product::class => [
            [
                'id'   => $productId,
                'type' => 'grouped',
                'sku'  => $sku,
            ],
        ],
    ]);
});

it('should return the grouped edit page', function () {
    // Arrange.
    $product = (new ProductFaker())->getGroupedProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'short_description' => fake()->sentence(),
        'description'       => fake()->paragraph(),
        'name'              => fake()->words(3, true),
        'price'             => fake()->randomFloat(2, 1, 1000),
        'weight'            => fake()->numberBetween(0, 100),
        'channel'           => core()->getCurrentChannelCode(),
        'locale'            => app()->getLocale(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name)
        ->assertSeeText($product->short_description)
        ->assertSeeText($product->description);
});

it('should fail the validation with errors when certain inputs are not provided when update in grouped product', function () {
    // Arrange.
    $product = (new ProductFaker())->getGroupedProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id))
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('name')
        ->assertUnprocessable();
});

it('should fail the validation with errors if certain data is not provided correctly in grouped product', function () {
    // Arrange.
    $product = (new ProductFaker())->getGroupedProductFactory()->create();

    // Act and Assert.
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
        ->assertJsonValidationErrorFor('visible_individually')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('guest_checkout')
        ->assertJsonValidationErrorFor('new')
        ->assertJsonValidationErrorFor('featured')
        ->assertUnprocessable();
});

it('should update the grouped product', function () {
    // Arrange.
    $product = (new ProductFaker())->getGroupedProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), $data = [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'short_description' => fake()->sentence(),
        'description'       => fake()->paragraph(),
        'name'              => fake()->words(3, true),
        'price'             => fake()->randomFloat(2, 1, 1000),
        'weight'            => fake()->numberBetween(0, 100),
        'channel'           => core()->getCurrentChannelCode(),
        'locale'            => app()->getLocale(),
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertModelWise([
        Product::class => [
            [
                'id'                  => $product->id,
                'type'                => $product->type,
                'sku'                 => $product->sku,
                'attribute_family_id' => 1,
                'parent_id'           => null,
                'additional'          => null,
            ],
        ],

        ProductFlat::class => [
            [
                'product_id'        => $product->id,
                'type'              => 'grouped',
                'sku'               => $product->sku,
                'url_key'           => $product->url_key,
                'name'              => $data['name'],
                'short_description' => $data['short_description'],
                'description'       => $data['description'],
                'price'             => $data['price'],
                'weight'            => $data['weight'],
                'locale'            => $data['locale'],
                'channel'           => $data['channel'],
            ],
        ],
    ]);
});

it('should update the grouped product options', function () {
    // Arrange.
    $product = (new ProductFaker())->getGroupedProductFactory()->create();

    $links = [];

    foreach ($product->grouped_products()->get() as $key => $simpleProduct) {
        $links[$simpleProduct->id]['associated_product_id'] = $simpleProduct->associated_product_id;
        $links[$simpleProduct->id]['sort_order'] = $key;
        $links[$simpleProduct->id]['qty'] = rand(10, 100);
    }

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'short_description' => $product->short_description,
        'description'       => $product->description,
        'name'              => $product->name,
        'price'             => $product->price,
        'weight'            => $product->weight,
        'channel'           => $product->channel,
        'links'             => $links,
        'locale'            => app()->getLocale(),
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertModelWise([
        Product::class => [
            [
                'id'                  => $product->id,
                'type'                => $product->type,
                'sku'                 => $product->sku,
                'attribute_family_id' => 1,
                'parent_id'           => null,
                'additional'          => null,
            ],
        ],
    ]);

    foreach ($links as $key => $link) {
        $this->assertModelWise([
            ProductGroupedProduct::class => [
                [
                    'product_id'            => $product->id,
                    'associated_product_id' => $link['associated_product_id'],
                    'qty'                   => $link['qty'],
                    'sort_order'            => $link['sort_order'],
                ],
            ],
        ]);
    }
});

it('should delete a grouped product', function () {
    // Arrange.
    $product = (new ProductFaker())->getGroupedProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);

    foreach ($product->grouped_products()->get() as $simpleProduct) {
        $this->assertDatabaseMissing('product_grouped_products', [
            'product_id'            => $product->id,
            'associated_product_id' => $simpleProduct->associated_product_id,
        ]);
    }
});
