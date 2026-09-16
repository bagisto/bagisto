<?php

use Webkul\Attribute\Models\Attribute;
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

    $product = Product::query()->where('sku', $sku)->first();

    expect($product)->not->toBeNull()
        ->and($product->type)->toBe('simple')
        ->and($product->attribute_family_id)->toBe(1);
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

        expect($attrValue)->not->toBeNull("Attribute value for '{$code}' should exist.")
            ->and($attrValue->text_value)->not->toBeEmpty("Text value for '{$code}' should not be empty.");
    }
});

it('should persist boolean attribute values after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $booleanAttributes = ['new', 'featured', 'visible_individually', 'guest_checkout'];

    foreach ($booleanAttributes as $code) {
        $attrValue = $product->attribute_values
            ->first(fn ($av) => $av->attribute->code === $code);

        expect($attrValue)->not->toBeNull("Attribute value for '{$code}' should exist.")
            ->and($attrValue->boolean_value)->toBeTrue("Boolean value for '{$code}' should be true.");
    }
});

it('should persist channel-scoped boolean attribute values after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $channel = core()->getDefaultChannelCode();

    $channelScoped = ['status'];

    foreach ($channelScoped as $code) {
        $attrValue = $product->attribute_values
            ->first(fn ($av) => $av->attribute->code === $code);

        expect($attrValue)->not->toBeNull("Attribute value for '{$code}' should exist.")
            ->and($attrValue->boolean_value)->toBeTrue("Boolean value for '{$code}' should be true.")
            ->and($attrValue->channel)->toBe($channel, "Channel for '{$code}' should be '{$channel}'.");
    }
});

it('should persist price as float attribute value after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $priceAttr = $product->attribute_values
        ->first(fn ($av) => $av->attribute->code === 'price');

    expect($priceAttr)->not->toBeNull()
        ->and((float) $priceAttr->float_value)->toBe(299.99);
});

it('should persist weight as text attribute value after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $weightAttr = $product->attribute_values
        ->first(fn ($av) => $av->attribute->code === 'weight');

    expect($weightAttr)->not->toBeNull()
        ->and($weightAttr->text_value)->toBe('15');
});

// ============================================================================
// Product Flat — Via Real Store + Update Flow
// ============================================================================

it('should populate product_flat with all indexed columns after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $flat = ProductFlat::query()->where('product_id', $product->id)->first();

    expect($flat)->not->toBeNull()
        ->and($flat->sku)->toBe($product->sku)
        ->and($flat->type)->toBe('simple')
        ->and($flat->product_id)->toBe($product->id)
        ->and($flat->attribute_family_id)->toBe(1)
        ->and($flat->name)->toBe('Test Simple Product')
        ->and($flat->url_key)->not->toBeEmpty()
        ->and($flat->short_description)->toBe('A short description for testing.')
        ->and($flat->description)->toBe('A full description paragraph for testing purposes.')
        ->and($flat->meta_title)->toBe('Test Meta Title')
        ->and($flat->meta_keywords)->toBe('test, simple, product')
        ->and($flat->meta_description)->toBe('Test meta description for SEO.')
        ->and($flat->product_number)->not->toBeEmpty()
        ->and((float) $flat->price)->toBe(299.99)
        ->and((float) $flat->weight)->toBe(15.0)
        ->and($flat->status)->toBeTruthy()
        ->and($flat->new)->toBeTruthy()
        ->and($flat->featured)->toBeTruthy()
        ->and($flat->visible_individually)->toBeTruthy()
        ->and($flat->locale)->toBe(app()->getLocale())
        ->and($flat->channel)->toBe(core()->getDefaultChannelCode());
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

    expect((int) $product->inventories()->first()->qty)->toBe(100);
});

// ============================================================================
// Channel Assignment — Via Real Store + Update Flow
// ============================================================================

it('should assign the simple product to the current channel after update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $this->assertDatabaseHas('product_channels', [
        'product_id' => $product->id,
        'channel_id' => core()->getDefaultChannel()->id,
    ]);
});

// ============================================================================
// Price and Inventory Indices — Via Real Store + Update Flow
// ============================================================================

it('should create price indices after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $priceIndices = ProductPriceIndex::query()->where('product_id', $product->id)->get();

    expect($priceIndices->count())->toBeGreaterThanOrEqual(1)
        ->and((float) $priceIndices->first()->min_price)->toBe(299.99);
});

it('should create inventory index after store and update', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    $this->assertDatabaseHas('product_inventory_indices', [
        'product_id' => $product->id,
    ]);

    $inventoryIndex = ProductInventoryIndex::query()->where('product_id', $product->id)->first();

    expect((int) $inventoryIndex->qty)->toBe(100);
});

// ============================================================================
// Update — Change Values and Verify
// ============================================================================

it('should update a simple product and reflect changes in all related tables', function () {
    $product = $this->storeAndUpdateSimpleProduct();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku' => $product->sku,
        'url_key' => $product->url_key,
        'name' => 'Changed Name',
        'short_description' => 'Changed short.',
        'description' => 'Changed description.',
        'price' => 49.99,
        'weight' => 5,
        'channel' => core()->getDefaultChannelCode(),
        'locale' => app()->getLocale(),
        'status' => 1,
        'visible_individually' => 1,
        'new' => 0,
        'featured' => 0,
        'guest_checkout' => 1,
    ])
        ->assertRedirect(route('admin.catalog.products.index'));

    $flat = ProductFlat::query()->where('product_id', $product->id)->first();

    $updatedProduct = Product::query()->with('attribute_values.attribute')->find($product->id);

    $nameAttr = $updatedProduct->attribute_values->first(fn ($av) => $av->attribute->code === 'name');

    $priceAttr = $updatedProduct->attribute_values->first(fn ($av) => $av->attribute->code === 'price');

    expect($flat->name)->toBe('Changed Name')
        ->and($flat->short_description)->toBe('Changed short.')
        ->and((float) $flat->price)->toBe(49.99)
        ->and((float) $flat->weight)->toBe(5.0)
        ->and($flat->new)->toBeFalsy()
        ->and($flat->featured)->toBeFalsy()
        ->and($nameAttr->text_value)->toBe('Changed Name')
        ->and((float) $priceAttr->float_value)->toBe(49.99);
});

// ============================================================================
// Edit Page — Security
// ============================================================================

it('should escape the attribute admin_name on the product edit page to prevent stored xss', function () {
    $payload = '"><img src=x onerror=alert(1)>';

    $attribute = Attribute::query()->where('code', 'name')->firstOrFail();

    $attribute->translations()
        ->where('locale', app()->getLocale())
        ->update(['name' => $payload]);

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    $content = get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->getContent();

    expect($content)
        ->not->toContain($payload)
        ->toContain(e($payload));
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
