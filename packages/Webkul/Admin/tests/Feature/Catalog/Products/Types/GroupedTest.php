<?php

use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Store
// ============================================================================

it('should store a grouped product and redirect to edit', function () {
    $this->loginAsAdmin();

    $sku = fake()->uuid();

    postJson(route('admin.catalog.products.store'), [
        'type' => 'grouped',
        'attribute_family_id' => 1,
        'sku' => $sku,
    ])
        ->assertOk()
        ->assertJsonStructure(['data' => ['redirect_url']]);

    $product = Product::where('sku', $sku)->first();

    expect($product)->not->toBeNull();
    expect($product->type)->toBe('grouped');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the edit page of a grouped product', function () {
    $this->loginAsAdmin();

    $product = $this->createGroupedProduct();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText($product->name);
});

// ============================================================================
// Product Flat — Via Real Store + Update Flow
// ============================================================================

it('should populate product_flat after store and update', function () {
    $product = $this->storeAndUpdateGroupedProduct();

    $flat = ProductFlat::where('product_id', $product->id)->first();

    expect($flat)->not->toBeNull();

    // Core fields
    expect($flat->sku)->toBe($product->sku);
    expect($flat->type)->toBe('grouped');
    expect($flat->name)->toBe('Test Grouped Product');

    // Grouped products skip price and weight.
    expect($flat->price)->toBeNull();
    expect($flat->weight)->toBeNull();

    // Boolean fields
    expect($flat->status)->toBeTruthy();
    expect($flat->visible_individually)->toBeTruthy();
});

// ============================================================================
// Grouped Product Links — Via Real Store + Update Flow
// ============================================================================

it('should create grouped product links after store and update', function () {
    $product = $this->storeAndUpdateGroupedProduct();

    expect($product->grouped_products)->toHaveCount(2);

    $firstLink = $product->grouped_products->firstWhere('sort_order', 0);
    expect($firstLink)->not->toBeNull();
    expect($firstLink->qty)->toBe(5);

    $secondLink = $product->grouped_products->firstWhere('sort_order', 1);
    expect($secondLink)->not->toBeNull();
    expect($secondLink->qty)->toBe(10);
});

it('should update grouped product link quantities', function () {
    $product = $this->storeAndUpdateGroupedProduct();

    // Build links payload using existing grouped product IDs.
    $links = [];

    foreach ($product->grouped_products as $key => $groupedProduct) {
        $links[$groupedProduct->id] = [
            'associated_product_id' => $groupedProduct->associated_product_id,
            'sort_order' => $key,
            'qty' => 99,
        ];
    }

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku' => $product->sku,
        'url_key' => $product->url_key,
        'name' => $product->name,
        'short_description' => $product->short_description,
        'description' => $product->description,
        'channel' => core()->getCurrentChannelCode(),
        'locale' => app()->getLocale(),
        'status' => 1,
        'visible_individually' => 1,
        'guest_checkout' => 1,
        'links' => $links,
    ])->assertRedirect(route('admin.catalog.products.index'));

    // Verify all links have updated qty.
    foreach ($links as $id => $link) {
        $this->assertDatabaseHas('product_grouped_products', [
            'id' => $id,
            'associated_product_id' => $link['associated_product_id'],
            'qty' => 99,
        ]);
    }
});

it('should not create inventory for a grouped product', function () {
    $product = $this->storeAndUpdateGroupedProduct();

    // Grouped products themselves don't have inventory.
    $this->assertDatabaseMissing('product_inventories', [
        'product_id' => $product->id,
    ]);
});

// ============================================================================
// Update Validation
// ============================================================================

it('should fail validation when required fields are missing on grouped product update', function () {
    $this->loginAsAdmin();

    $product = $this->createGroupedProduct();

    // Grouped products do not require price or weight.
    putJson(route('admin.catalog.products.update', $product->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a grouped product and clean up all related tables', function () {
    $product = $this->storeAndUpdateGroupedProduct();
    $productId = $product->id;

    deleteJson(route('admin.catalog.products.delete', $productId))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', ['id' => $productId]);
    $this->assertDatabaseMissing('product_flat', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_attribute_values', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_grouped_products', ['product_id' => $productId]);
});
