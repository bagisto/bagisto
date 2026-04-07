<?php

use Illuminate\Support\Facades\Event;
use Webkul\Product\Models\Product;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

// ============================================================================
// Datasets
// ============================================================================

dataset('product_types', [
    'simple' => ['simple'],
    'virtual' => ['virtual'],
    'configurable' => ['configurable'],
    'downloadable' => ['downloadable'],
    'grouped' => ['grouped'],
    'bundle' => ['bundle'],
    'booking' => ['booking'],
]);

// ============================================================================
// Index
// ============================================================================

it('should return the product index page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.products.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.index.title'))
        ->assertSeeText(trans('admin::app.catalog.products.index.create-btn'));
});

it('should return product listing via datagrid', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    getJson(route('admin.catalog.products.index'), [
        'X-Requested-With' => 'XMLHttpRequest',
    ])
        ->assertOk()
        ->assertJsonPath('records.0.product_id', $product->id);
});

it('should deny guest access to the product index page', function () {
    get(route('admin.catalog.products.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store — All Product Types
// ============================================================================

it('should store a [type] product and redirect to edit', function (string $type) {
    $this->loginAsAdmin();

    $sku = fake()->uuid();

    $payload = [
        'type' => $type,
        'attribute_family_id' => 1,
        'sku' => $sku,
    ];

    $response = postJson(route('admin.catalog.products.store'), $payload)->assertOk();

    if ($type === 'configurable') {
        // Configurable without super_attributes returns attribute options for
        // the UI to render the variant configuration step.
        $response->assertJsonStructure(['data' => ['attributes']]);
    } else {
        $response->assertJsonStructure(['data' => ['redirect_url']]);

        $this->assertDatabaseHas('products', [
            'sku' => $sku,
            'type' => $type,
        ]);
    }
})->with('product_types');

// ============================================================================
// Store — Validation
// ============================================================================

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('attribute_family_id')
        ->assertJsonValidationErrorFor('sku');
});

it('should fail validation when sku already exists', function () {
    $existing = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type' => 'simple',
        'attribute_family_id' => 1,
        'sku' => $existing->sku,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('sku');
});

// ============================================================================
// Store — Events
// ============================================================================

it('should dispatch create events when storing a [type] product', function (string $type) {
    Event::fake();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type' => $type,
        'attribute_family_id' => 1,
        'sku' => fake()->uuid(),
    ])
        ->assertOk();

    if ($type === 'configurable') {
        // Without super_attributes the configurable type returns attribute
        // options without creating a product, so no events are dispatched.
        Event::assertNotDispatched('catalog.product.create.before');
        Event::assertNotDispatched('catalog.product.create.after');
    } else {
        Event::assertDispatched('catalog.product.create.before');
        Event::assertDispatched('catalog.product.create.after');
    }
})->with('product_types');

// ============================================================================
// Edit
// ============================================================================

it('should return the edit page of a product', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'));
});

it('should return 404 for a non-existent product edit page', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', 99999))
        ->assertNotFound();
});

// ============================================================================
// Copy
// ============================================================================

it('should copy an existing product', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.copy', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.product-copied'));

    $copiedProduct = Product::latest('id')->first();

    expect($copiedProduct->id)->not->toBe($product->id);
    expect($copiedProduct->sku)->toStartWith('temporary-sku-');
});

// ============================================================================
// Destroy
// ============================================================================

it('should delete a product', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

it('should return error when deleting a non-existent product', function () {
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', 99999))
        ->assertServerError()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-failed'));
});

it('should dispatch events when deleting a product', function () {
    Event::fake();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk();

    Event::assertDispatched('catalog.product.delete.before');
    Event::assertDispatched('catalog.product.delete.after');
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete products', function () {
    $products = collect([
        $this->createSimpleProduct(),
        $this->createSimpleProduct(),
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_delete'), [
        'indices' => $products->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-delete-success'));

    foreach ($products as $product) {
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
});

it('should fail mass delete validation when indices are missing', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_delete'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('indices');
});

// ============================================================================
// Mass Update
// ============================================================================

it('should mass update product status to active', function () {
    $products = collect([
        $this->createSimpleProduct(),
        $this->createSimpleProduct(),
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_update'), [
        'indices' => $products->pluck('id')->toArray(),
        'value' => 1,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-update-success'));
});

it('should mass update product status to inactive', function () {
    $products = collect([
        $this->createSimpleProduct(),
        $this->createSimpleProduct(),
    ]);

    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_update'), [
        'indices' => $products->pluck('id')->toArray(),
        'value' => 0,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-update-success'));
});

it('should fail mass update validation when indices or value are missing', function () {
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_update'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('indices')
        ->assertJsonValidationErrorFor('value');
});

// ============================================================================
// Search
// ============================================================================

it('should search products by name', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.search', [
        'query' => $product->name,
    ]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $product->id)
        ->assertJsonPath('data.0.sku', $product->sku);
});

it('should return empty results for empty search query', function () {
    $this->loginAsAdmin();

    get(route('admin.catalog.products.search'))
        ->assertOk()
        ->assertJsonPath('data', []);
});
