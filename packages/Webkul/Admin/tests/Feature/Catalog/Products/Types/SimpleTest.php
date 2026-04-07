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

it('should store a simple product and redirect to edit', function () {
    $this->loginAsAdmin();

    $sku = fake()->uuid();

    postJson(route('admin.catalog.products.store'), [
        'type' => 'simple',
        'attribute_family_id' => 1,
        'sku' => $sku,
    ])
        ->assertOk()
        ->assertJsonStructure(['data' => ['redirect_url']]);

    $product = Product::where('sku', $sku)->first();

    expect($product)->not->toBeNull();
    expect($product->type)->toBe('simple');
    expect($product->attribute_family_id)->toBe(1);
});

// ============================================================================
// Edit
// ============================================================================

it('should return the edit page of a simple product', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name)
        ->assertSeeText($product->short_description);
});

// ============================================================================
// Product Attribute Values — Via Real Store + Update Flow
// ============================================================================

it('should persist text attribute values after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

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
    $product = $this->storeAndUpdateSimpleProduct();

    $booleanAttributes = ['new', 'featured', 'visible_individually', 'guest_checkout'];

    foreach ($booleanAttributes as $code) {
        $attrValue = $product->attribute_values
            ->first(fn ($av) => $av->attribute->code === $code);

        expect($attrValue)->not->toBeNull("Attribute value for '{$code}' should exist.");
        expect($attrValue->boolean_value)->toBeTrue("Boolean value for '{$code}' should be true.");
    }
});

it('should persist channel-scoped boolean attribute values after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();
    $channel = core()->getCurrentChannelCode();

    $channelScoped = ['status'];

    foreach ($channelScoped as $code) {
        $attrValue = $product->attribute_values
            ->first(fn ($av) => $av->attribute->code === $code);

        expect($attrValue)->not->toBeNull("Attribute value for '{$code}' should exist.");
        expect($attrValue->boolean_value)->toBeTrue("Boolean value for '{$code}' should be true.");
        expect($attrValue->channel)->toBe($channel, "Channel for '{$code}' should be '{$channel}'.");
    }
});

it('should persist price as float attribute value after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $priceAttr = $product->attribute_values
        ->first(fn ($av) => $av->attribute->code === 'price');

    expect($priceAttr)->not->toBeNull();
    expect((float) $priceAttr->float_value)->toBe(299.99);
});

it('should persist weight as text attribute value after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $weightAttr = $product->attribute_values
        ->first(fn ($av) => $av->attribute->code === 'weight');

    expect($weightAttr)->not->toBeNull();
    expect($weightAttr->text_value)->toBe('15');
});

// ============================================================================
// Product Flat — Via Real Store + Update Flow
// ============================================================================

it('should populate product_flat with all indexed columns after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $flat = ProductFlat::where('product_id', $product->id)->first();

    expect($flat)->not->toBeNull();

    // Core fields
    expect($flat->sku)->toBe($product->sku);
    expect($flat->type)->toBe('simple');
    expect($flat->product_id)->toBe($product->id);
    expect($flat->attribute_family_id)->toBe(1);

    // Text fields indexed from attribute values
    expect($flat->name)->toBe('Test Simple Product');
    expect($flat->url_key)->not->toBeEmpty();
    expect($flat->short_description)->toBe('A short description for testing.');
    expect($flat->description)->toBe('A full description paragraph for testing purposes.');
    expect($flat->meta_title)->toBe('Test Meta Title');
    expect($flat->meta_keywords)->toBe('test, simple, product');
    expect($flat->meta_description)->toBe('Test meta description for SEO.');
    expect($flat->product_number)->not->toBeEmpty();

    // Numeric fields
    expect((float) $flat->price)->toBe(299.99);
    expect((float) $flat->weight)->toBe(15.0);

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

it('should create inventory after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $this->assertDatabaseHas('product_inventories', [
        'product_id' => $product->id,
        'inventory_source_id' => 1,
    ]);

    $inventory = $product->inventories()->first();

    expect((int) $inventory->qty)->toBe(100);
});

// ============================================================================
// Channel Assignment — Via Real Store + Update Flow
// ============================================================================

it('should assign the simple product to the current channel after update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $this->assertDatabaseHas('product_channels', [
        'product_id' => $product->id,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);
});

// ============================================================================
// Price and Inventory Indices — Via Real Store + Update Flow
// ============================================================================

it('should create price indices after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $priceIndices = ProductPriceIndex::where('product_id', $product->id)->get();

    // Price indices are created per customer group (guest + seeded groups).
    expect($priceIndices->count())->toBeGreaterThanOrEqual(1);

    $firstIndex = $priceIndices->first();
    expect((float) $firstIndex->min_price)->toBe(299.99);
});

it('should create inventory index after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $this->assertDatabaseHas('product_inventory_indices', [
        'product_id' => $product->id,
    ]);

    $inventoryIndex = ProductInventoryIndex::where('product_id', $product->id)->first();

    expect((int) $inventoryIndex->qty)->toBe(100);
});

// ============================================================================
// Update — Change Values and Verify
// ============================================================================

it('should update a simple product and reflect changes in all related tables', function () {
    // Create the product via the real store + update flow.
    $product = $this->storeAndUpdateSimpleProduct();

    // Update again with different values to verify changes propagate.
    putJson(route('admin.catalog.products.update', $product->id), [
        'sku' => $product->sku,
        'url_key' => $product->url_key,
        'name' => 'Changed Name',
        'short_description' => 'Changed short.',
        'description' => 'Changed description.',
        'price' => 49.99,
        'weight' => 5,
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

    expect($flat->name)->toBe('Changed Name');
    expect($flat->short_description)->toBe('Changed short.');
    expect((float) $flat->price)->toBe(49.99);
    expect((float) $flat->weight)->toBe(5.0);
    expect($flat->new)->toBeFalsy();
    expect($flat->featured)->toBeFalsy();

    // Verify attribute values are updated.
    $updatedProduct = Product::with('attribute_values.attribute')->find($product->id);

    $nameAttr = $updatedProduct->attribute_values->first(fn ($av) => $av->attribute->code === 'name');
    expect($nameAttr->text_value)->toBe('Changed Name');

    $priceAttr = $updatedProduct->attribute_values->first(fn ($av) => $av->attribute->code === 'price');
    expect((float) $priceAttr->float_value)->toBe(49.99);
});

// ============================================================================
// Update Validation
// ============================================================================

it('should fail validation when required fields are missing on simple product update', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('price')
        ->assertJsonValidationErrorFor('weight')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description');
});

it('should fail validation when boolean fields have invalid values on simple product update', function () {
    $product = $this->createSimpleProduct();

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

it('should delete a simple product and clean up all related tables', function () {
    $product = $this->storeAndUpdateSimpleProduct();
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
