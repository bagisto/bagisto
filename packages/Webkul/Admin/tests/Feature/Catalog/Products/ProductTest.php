<?php

use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Contracts\ProductFlat;
use Webkul\Product\Models\Product;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should return the product index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.products.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.index.title'))
        ->assertSeeText(trans('admin::app.catalog.products.index.create-btn'));
});

it('should copy the existing product', function () {
    // Arrange.
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.products.copy', $product->id))
        ->assertRedirect(route('admin.catalog.products.edit', $productId = $product->id + 1))
        ->isRedirection();

    $this->assertModelWise([
        Product::class => [
            [
                'id' => $productId,
            ],
        ],
    ]);
});

it('should perform the mass action from update status for products', function () {
    // Arrange.
    $products = (new ProductFaker)->getSimpleProductFactory()->count(2)->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_update'), [
        'indices' => $products->pluck('id')->toArray(),
        'value'   => 1,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-update-success'));

    foreach ($products as $product) {
        $this->assertModelWise([
            ProductFlat::class => [
                [
                    'product_id' => $product->id,
                    'sku'        => $product->sku,
                    'status'     => 1,
                ],
            ],
        ]);
    }
});

it('should perform the mass action for delete for products', function () {
    // Arrange.
    $products = (new ProductFaker)->getSimpleProductFactory()->count(2)->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_delete'), [
        'indices' => $products->pluck('id')->toArray(),
        'value'   => 1,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-delete-success'));

    foreach ($products as $product) {
        $this->assertDatabaseMissing('product_flat', [
            'status'     => 1,
            'product_id' => $product->id,
        ]);
    }
});

it('should search the product', function () {
    // Arrange.
    $product = (new ProductFaker)->getSimpleProductFactory()->count(2)->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.products.search', [
        'query' => $product[0]->name,
    ]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $product[0]->id)
        ->assertJsonPath('data.0.name', $product[0]->name)
        ->assertJsonPath('data.0.sku', $product[0]->sku);
});
