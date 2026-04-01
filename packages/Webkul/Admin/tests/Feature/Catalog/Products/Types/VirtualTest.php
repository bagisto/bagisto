<?php

use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Models\ProductInventoryIndex;
use Webkul\Product\Models\ProductPriceIndex;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Store
// ============================================================================

it('should store a virtual product and redirect to edit', function () {
    $this->loginAsAdmin();

    $sku = fake()->uuid();

    postJson(route('admin.catalog.products.store'), [
        'type' => 'virtual',
        'attribute_family_id' => 1,
        'sku' => $sku,
    ])
        ->assertOk()
        ->assertJsonStructure(['data' => ['redirect_url']]);

    $product = Product::where('sku', $sku)->first();

    expect($product)->not->toBeNull();
    expect($product->type)->toBe('virtual');
    expect($product->attribute_family_id)->toBe(1);
});

// ============================================================================
// Edit
// ============================================================================

it('should return the edit page of a virtual product', function () {
    $product = $this->createVirtualProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name);
});

// ============================================================================
// Product Attribute Values — Via Real Store + Update Flow
// ============================================================================

it('should persist text attribute values after store and update', function () {
    $product = $this->storeAndUpdateVirtualProduct();

    $textAttributes = [
        'sku', 'name', 'url_key', 'short_description', 'description',
        'meta_title', 'meta_keywords', 'meta_description', 'product_number',
    ];

    foreach ($textAttributes as $code) {
        $attrValue = $product->attribute_values
            ->first(fn ($av) => $av->attribute->code === $code);

        expect($attrValue)->not->toBeNull("Attribute value for '{$code}' should exist.");
        expect($attrValue->text_value)->not->toBeEmpty("Text value for '{$code}' should not be empty.");
    }
});

it('should persist boolean attribute values after store and update', function () {
    $product = $this->storeAndUpdateVirtualProduct();

    $booleanAttributes = ['new', 'featured', 'visible_individually', 'guest_checkout'];

    foreach ($booleanAttributes as $code) {
        $attrValue = $product->attribute_values
            ->first(fn ($av) => $av->attribute->code === $code);

        expect($attrValue)->not->toBeNull("Attribute value for '{$code}' should exist.");
        expect($attrValue->boolean_value)->toBeTrue("Boolean value for '{$code}' should be true.");
    }
});

it('should persist channel-scoped boolean attribute values after store and update', function () {
    $product = $this->storeAndUpdateVirtualProduct();
    $channel = core()->getCurrentChannelCode();

    $attrValue = $product->attribute_values
        ->first(fn ($av) => $av->attribute->code === 'status');

    expect($attrValue)->not->toBeNull();
    expect($attrValue->boolean_value)->toBeTrue();
    expect($attrValue->channel)->toBe($channel);
});

it('should persist price as float attribute value after store and update', function () {
    $product = $this->storeAndUpdateVirtualProduct();

    $priceAttr = $product->attribute_values
        ->first(fn ($av) => $av->attribute->code === 'price');

    expect($priceAttr)->not->toBeNull();
    expect((float) $priceAttr->float_value)->toBe(149.99);
});

it('should not create weight attribute value for a virtual product', function () {
    $product = $this->storeAndUpdateVirtualProduct();

    // Virtual products skip weight, length, width, height, and depth.
    $weightAttr = $product->attribute_values
        ->first(fn ($av) => $av->attribute->code === 'weight');

    expect($weightAttr)->toBeNull();
});

// ============================================================================
// Product Flat — Via Real Store + Update Flow
// ============================================================================

it('should populate product_flat with all indexed columns after store and update', function () {
    $product = $this->storeAndUpdateVirtualProduct();

    $flat = ProductFlat::where('product_id', $product->id)->first();

    expect($flat)->not->toBeNull();

    // Core fields
    expect($flat->sku)->toBe($product->sku);
    expect($flat->type)->toBe('virtual');
    expect($flat->product_id)->toBe($product->id);
    expect($flat->attribute_family_id)->toBe(1);

    // Text fields indexed from attribute values
    expect($flat->name)->toBe('Test Virtual Product');
    expect($flat->url_key)->not->toBeEmpty();
    expect($flat->short_description)->toBe('A short description for the virtual product.');
    expect($flat->description)->toBe('A full description paragraph for the virtual product.');
    expect($flat->meta_title)->toBe('Virtual Meta Title');
    expect($flat->meta_keywords)->toBe('virtual, test, product');
    expect($flat->meta_description)->toBe('Virtual meta description for SEO.');
    expect($flat->product_number)->not->toBeEmpty();

    // Numeric fields
    expect((float) $flat->price)->toBe(149.99);

    // Virtual products do not have weight in product_flat.
    expect($flat->weight)->toBeNull();

    // Boolean fields
    expect($flat->status)->toBeTruthy();
    expect($flat->new)->toBeTruthy();
    expect($flat->featured)->toBeTruthy();
    expect($flat->visible_individually)->toBeTruthy();

    // Locale and channel
    expect($flat->locale)->toBe(app()->getLocale());
    expect($flat->channel)->toBe(core()->getCurrentChannelCode());
});

// ============================================================================
// Inventory — Via Real Store + Update Flow
// ============================================================================

it('should create inventory for a virtual product', function () {
    $product = $this->storeAndUpdateVirtualProduct();

    $this->assertDatabaseHas('product_inventories', [
        'product_id' => $product->id,
        'inventory_source_id' => 1,
    ]);

    $inventory = $product->inventories()->first();

    expect((int) $inventory->qty)->toBe(50);
});

// ============================================================================
// Channel Assignment — Via Real Store + Update Flow
// ============================================================================

it('should assign the virtual product to the current channel after update', function () {
    $product = $this->storeAndUpdateVirtualProduct();

    $this->assertDatabaseHas('product_channels', [
        'product_id' => $product->id,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);
});

// ============================================================================
// Price and Inventory Indices — Via Real Store + Update Flow
// ============================================================================

it('should create price indices after store and update', function () {
    $product = $this->storeAndUpdateVirtualProduct();

    $priceIndices = ProductPriceIndex::where('product_id', $product->id)->get();

    // Price indices are created per customer group (guest + seeded groups).
    expect($priceIndices->count())->toBeGreaterThanOrEqual(1);

    $firstIndex = $priceIndices->first();
    expect((float) $firstIndex->min_price)->toBe(149.99);
});

it('should create inventory index after store and update', function () {
    $product = $this->storeAndUpdateVirtualProduct();

    $this->assertDatabaseHas('product_inventory_indices', [
        'product_id' => $product->id,
    ]);

    $inventoryIndex = ProductInventoryIndex::where('product_id', $product->id)->first();

    expect((int) $inventoryIndex->qty)->toBe(50);
});

// ============================================================================
// Update — Change Values and Verify
// ============================================================================

it('should update a virtual product and reflect changes in all related tables', function () {
    // Create the product via the real store + update flow.
    $product = $this->storeAndUpdateVirtualProduct();

    // Update again with different values to verify changes propagate.
    putJson(route('admin.catalog.products.update', $product->id), [
        'sku' => $product->sku,
        'url_key' => $product->url_key,
        'name' => 'Changed Virtual Name',
        'short_description' => 'Changed virtual short.',
        'description' => 'Changed virtual description.',
        'price' => 29.99,
        'channel' => core()->getCurrentChannelCode(),
        'locale' => app()->getLocale(),
        'status' => 1,
        'visible_individually' => 1,
        'new' => 0,
        'featured' => 0,
        'guest_checkout' => 1,
    ])
        ->assertRedirect(route('admin.catalog.products.index'));

    // Verify product_flat reflects the changed values.
    $flat = ProductFlat::where('product_id', $product->id)->first();

    expect($flat->name)->toBe('Changed Virtual Name');
    expect($flat->short_description)->toBe('Changed virtual short.');
    expect((float) $flat->price)->toBe(29.99);
    expect($flat->new)->toBeFalsy();
    expect($flat->featured)->toBeFalsy();

    // Verify attribute values are updated.
    $updatedProduct = Product::with('attribute_values.attribute')->find($product->id);

    $nameAttr = $updatedProduct->attribute_values->first(fn ($av) => $av->attribute->code === 'name');
    expect($nameAttr->text_value)->toBe('Changed Virtual Name');

    $priceAttr = $updatedProduct->attribute_values->first(fn ($av) => $av->attribute->code === 'price');
    expect((float) $priceAttr->float_value)->toBe(29.99);
});

// ============================================================================
// Update Validation
// ============================================================================

it('should fail validation when required fields are missing on virtual product update', function () {
    $product = $this->createVirtualProduct();

    $this->loginAsAdmin();

    // Virtual products do not require weight.
    putJson(route('admin.catalog.products.update', $product->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('price')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description');
});

it('should fail validation when boolean fields have invalid values on virtual product update', function () {
    $product = $this->createVirtualProduct();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'visible_individually' => 'invalid',
        'status' => 'invalid',
        'guest_checkout' => 'invalid',
        'new' => 'invalid',
        'featured' => 'invalid',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('visible_individually')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('guest_checkout')
        ->assertJsonValidationErrorFor('new')
        ->assertJsonValidationErrorFor('featured');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a virtual product and clean up all related tables', function () {
    $product = $this->storeAndUpdateVirtualProduct();
    $productId = $product->id;

    deleteJson(route('admin.catalog.products.delete', $productId))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', ['id' => $productId]);
    $this->assertDatabaseMissing('product_flat', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_attribute_values', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_inventories', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_channels', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_price_indices', ['product_id' => $productId]);
    $this->assertDatabaseMissing('product_inventory_indices', ['product_id' => $productId]);
});
