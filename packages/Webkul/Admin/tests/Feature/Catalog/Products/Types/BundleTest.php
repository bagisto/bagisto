<?php

use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Product\Models\ProductFlat;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should fail the validation with errors when certain inputs are not provided when store in bundle product', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'))
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('attribute_family_id')
        ->assertJsonValidationErrorFor('sku')
        ->assertUnprocessable();
});

it('should return the create page of bundle product', function () {
    // Arrange.
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), $data = [
        'type'                => 'bundle',
        'attribute_family_id' => 1,
        'sku'                 => fake()->slug(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertModelWise([
        ProductModel::class => [
            [
                'id'   => $productId,
                'type' => $data['type'],
                'sku'  => $data['sku'],
            ],
        ],
    ]);
});

it('should return the edit page of bundle product', function () {
    // Arrange.
    $product = (new ProductFaker)->getBundleProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name)
        ->assertSeeText($product->short_description)
        ->assertSeeText($product->description);
});

it('should fail the validation with errors when certain inputs are not provided when update in bundle product', function () {
    // Arrange.
    $product = (new ProductFaker)->getBundleProductFactory()->create();

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

it('should fail the validation with errors if certain data is not provided correctly in bundle product', function () {
    // Arrange.
    $product = (new ProductFaker)->getBundleProductFactory()->create();

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

it('should update the bundle product', function () {
    // Arrange.
    $product = (new ProductFaker)->getBundleProductFactory()->create();

    $options = [];

    $bundleOptions = $product->bundle_options();

    foreach ($bundleOptions as $key => $option) {
        $products = [];

        foreach ($option->bundle_option_products as $key => $bundleOption) {
            $products[$option->id]['product_id'] = $bundleOption->product_id;
            $products[$option->id]['sort_order'] = $key;
            $products[$option->id]['qty'] = 1;
        }

        $options[$option->id] = [
            app()->getLocale() => [
                'label' => fake()->words(3, true),
            ],
            'type'        => fake()->randomElement(['select', 'radio', 'checkbox', 'multiselect']),
            'is_required' => '1',
            'sort_order'  => $key,
            'products'    => $products,
        ];
    }

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), $data = [
        'sku'                  => $product->sku,
        'url_key'              => $product->url_key,
        'short_description'    => fake()->sentence(),
        'description'          => fake()->paragraph(),
        'name'                 => fake()->words(3, true),
        'price'                => fake()->randomFloat(2, 1, 1000),
        'weight'               => fake()->numberBetween(0, 100),
        'channel'              => core()->getCurrentChannelCode(),
        'locale'               => app()->getLocale(),
        'bundle_options'       => $options,
        'new'                  => '1',
        'featured'             => '1',
        'visible_individually' => '1',
        'status'               => '1',
        'guest_checkout'       => '1',
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertModelWise([
        ProductModel::class => [
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
                'url_key'           => $product->url_key,
                'type'              => 'bundle',
                'product_id'        => $product->id,
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

    foreach ($bundleOptions as $product) {
        $product->refresh();

        $this->assertModelWise([
            ProductFlat::class => [
                [
                    'url_key'           => $product->url_key,
                    'type'              => 'simple',
                    'name'              => $product->name,
                    'short_description' => $product->short_description,
                    'description'       => $product->description,
                    'price'             => $product->price,
                    'weight'            => $product->weight,
                    'locale'            => $data['locale'],
                    'product_id'        => $product->id,
                    'channel'           => $data['channel'],
                ],
            ],
        ]);
    }
});

it('should delete a bundle product', function () {
    // Arrange.
    $product = (new ProductFaker)->getBundleProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);

    foreach ($product->bundle_options() as $option) {
        $this->assertDatabaseMissing('product_bundle_options', [
            'id' => $option->id,
        ]);

        $this->assertDatabaseMissing('product_bundle_option_products', [
            'id' => $option->id,
        ]);
    }
});
